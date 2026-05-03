/**
 * MedOS Professional Agora RTC Handler
 * V10 - The "Google Meet" Edition (Smart Handshake)
 */
class AgoraHandler {
    constructor(appId, channelName, token = null, uid = null, onJoin = null) {
        this.appId = appId;
        this.channel = channelName;
        this.token = token;
        this.uid = uid;
        this.onJoin = onJoin;
        this.client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
        this.localTracks = { videoTrack: null, audioTrack: null };
        this.remoteUsers = {};
        this.debugEl = document.getElementById("agora-debug");
        
        this.isJoining = false;
        this.isJoined = false;
        this.discoveryInterval = null;
    }

    log(msg, isError = false) {
        console.log(`Agora [${this.channel}]: ${msg}`);
        if(this.debugEl) {
            this.debugEl.innerText = msg;
            this.debugEl.style.color = isError ? "#f87171" : "#2dd4bf";
        }
    }

    async join() {
        if(this.isJoined || this.isJoining) return;
        this.isJoining = true;
        this.log(`Connecting...`);

        // Set up events
        this.client.on("user-published", (user, mediaType) => this.handleUserPublished(user, mediaType));
        this.client.on("user-left", (user) => {
            this.log(`Peer left`);
            delete this.remoteUsers[user.uid];
        });

        try {
            const tokenToUse = (this.token === '' || this.token === null) ? null : this.token;
            await this.client.join(this.appId, this.channel, tokenToUse, this.uid);
            
            this.isJoined = true;
            this.isJoining = false;
            this.log(`Online`);

            // --- THE SMART HANDSHAKE ---
            // Poll for peers every 1 second until connected
            this.discoveryInterval = setInterval(() => {
                if(this.client.remoteUsers.length > 0) {
                    this.client.remoteUsers.forEach(user => {
                        if(!this.remoteUsers[user.uid]) {
                            this.handleUserPublished(user, "video");
                            this.handleUserPublished(user, "audio");
                        }
                    });
                }
            }, 1000);
            
            try {
                this.localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
                this.localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack({
                    encoderConfig: { width: 640, height: 480, frameRate: 15 }
                });
                
                this.localTracks.videoTrack.play("local-video");
                await this.client.publish(Object.values(this.localTracks));
            } catch (pError) {
                this.log("Camera Blocked", true);
            }

        } catch (error) {
            this.isJoining = false;
            this.log("Connection Failed", true);
        }
    }

    async handleUserPublished(user, mediaType) {
        try {
            await this.client.subscribe(user, mediaType);
            
            if (mediaType === "video") {
                this.remoteUsers[user.uid] = user;
                // Instant UI Reveal
                const remoteDiv = document.getElementById("remote-video");
                if(remoteDiv) {
                    user.videoTrack.play("remote-video");
                    if(this.onJoin) this.onJoin(); // Hide placeholders immediately
                }
            }
            if (mediaType === "audio") {
                user.audioTrack.play();
            }
        } catch (e) {
            // Silently retry via the Discovery Interval
        }
    }

    async leave() {
        if(this.discoveryInterval) clearInterval(this.discoveryInterval);
        this.isJoined = false;
        this.isJoining = false;
        for (let trackName in this.localTracks) {
            var track = this.localTracks[trackName];
            if (track) { track.stop(); track.close(); }
        }
        await this.client.leave();
    }

    async forceSync() {
        this.client.remoteUsers.forEach(user => {
            this.handleUserPublished(user, "video");
            this.handleUserPublished(user, "audio");
        });
    }

    async retryTracks() {
        window.location.reload();
    }
}
