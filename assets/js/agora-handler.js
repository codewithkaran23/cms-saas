/**
 * MedOS Professional Agora RTC Handler
 * V6 - Production Grade (Proxy Removed & Existing User Sync)
 */
class AgoraHandler {
    constructor(appId, channelName, token = null, uid = null) {
        this.appId = appId;
        this.channel = channelName;
        this.token = token;
        this.uid = uid;
        this.client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
        this.localTracks = { videoTrack: null, audioTrack: null };
        this.remoteUsers = {};
        this.debugEl = document.getElementById("agora-debug");
        
        this.isJoining = false;
        this.isJoined = false;
    }

    log(msg, isError = false) {
        console.log(`Agora Debug: ${msg}`);
        if(this.debugEl) {
            this.debugEl.innerText = msg;
            this.debugEl.style.color = isError ? "#f87171" : "#2dd4bf";
        }
    }

    async join() {
        if(this.isJoined || this.isJoining) return;
        this.isJoining = true;
        this.log(`Connecting...`);

        if(!this.appId) {
            this.isJoining = false;
            return this.log("Missing App ID", true);
        }

        // Set up listeners
        this.client.on("user-published", (user, mediaType) => this.handleUserPublished(user, mediaType));
        this.client.on("user-left", (user) => {
            this.log(`Peer ${user.uid} left`);
            delete this.remoteUsers[user.uid];
        });

        try {
            const tokenToUse = (this.token === '' || this.token === null) ? null : this.token;
            const joinedUid = await this.client.join(this.appId, this.channel, tokenToUse, this.uid);
            
            this.isJoined = true;
            this.isJoining = false;
            this.log(`Joined as UID ${joinedUid}`);
            
            // --- CRITICAL FIX: SUBSCRIBE TO USERS ALREADY IN THE ROOM ---
            this.client.remoteUsers.forEach(user => {
                if(user.hasVideo) this.handleUserPublished(user, "video");
                if(user.hasAudio) this.handleUserPublished(user, "audio");
            });
            
            // Create and publish local tracks
            try {
                this.localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
                this.localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack({
                    encoderConfig: { width: 640, height: 480, frameRate: 15 }
                });
                
                this.localTracks.videoTrack.play("local-video");
                await this.client.publish(Object.values(this.localTracks));
                this.log(`Live Room: ${this.channel}`);
            } catch (pError) {
                this.log("Camera/Mic Blocked", true);
            }

        } catch (error) {
            this.isJoining = false;
            this.log("Join Error: " + error.message, true);
        }
    }

    async handleUserPublished(user, mediaType) {
        this.log(`Peer ${user.uid} (${mediaType}) detected`);
        
        // Small delay to ensure browser is ready for the stream
        setTimeout(async () => {
            try {
                await this.client.subscribe(user, mediaType);
                this.log(`Subscribed to Peer ${user.uid}`);

                if (mediaType === "video") {
                    this.remoteUsers[user.uid] = user;
                    // Auto-hide the "Waiting" screen
                    document.querySelectorAll(".placeholder-overlay").forEach(el => el.style.display = "none");
                    
                    const remoteDiv = document.getElementById("remote-video");
                    if(remoteDiv) {
                        user.videoTrack.play("remote-video");
                    }
                }
                if (mediaType === "audio") {
                    user.audioTrack.play();
                }
            } catch (e) {
                console.warn("Auto-subscribe failed, use Force Sync if needed.");
            }
        }, 500);
    }

    async leave() {
        this.isJoined = false;
        this.isJoining = false;
        for (let trackName in this.localTracks) {
            var track = this.localTracks[trackName];
            if (track) {
                track.stop();
                track.close();
            }
        }
        await this.client.leave();
    }

    async forceSync() {
        this.log("Force Syncing...");
        this.client.remoteUsers.forEach(user => {
            this.handleUserPublished(user, "video");
            this.handleUserPublished(user, "audio");
        });
    }

    async retryTracks() {
        window.location.reload();
    }
}
