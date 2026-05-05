/**
 * MedOS Professional Agora RTC Handler
 * V13 - Reliable Local State (Don't trust SDK enabled state)
 */
class AgoraHandler {
    constructor(appId, channelName, token = null, uid = null, onJoin = null, onReady = null) {
        this.appId = appId;
        this.channel = channelName;
        this.token = token;
        this.uid = uid;
        this.onJoin = onJoin;
        this.onReady = onReady; // Callback for when tracks are published
        this.client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
        this.localTracks = { videoTrack: null, audioTrack: null };
        this.remoteUsers = {};
        
        this.isJoining = false;
        this.isJoined = false;
        this.isAudioEnabled = true;
        this.isVideoEnabled = true;
        this.discoveryInterval = null;
    }

    log(msg, isError = false) {
        console.log(`Agora: ${msg}`);
        const el = document.getElementById("agora-debug");
        if(el) {
            el.innerText = msg;
            el.style.color = isError ? "#f87171" : "#2dd4bf";
        }
    }

    async join() {
        if(this.isJoined || this.isJoining) return;
        this.isJoining = true;
        this.log(`Connecting...`);

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
                
                this.log("Streaming Active");
                if(this.onReady) this.onReady(); // UNLOCK BUTTONS
            } catch (pError) {
                this.log("Hardware Blocked", true);
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
                const remoteDiv = document.getElementById("remote-video");
                if(remoteDiv) {
                    user.videoTrack.play("remote-video");
                    if(this.onJoin) this.onJoin();
                }
            }
            if (mediaType === "audio") {
                user.audioTrack.play();
            }
        } catch (e) { }
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

    async toggleAudio() {
        if (!this.localTracks.audioTrack) {
            this.log("Audio not ready", true);
            return this.isAudioEnabled === false; // correctly returns current isMuted state
        }
        this.isAudioEnabled = !this.isAudioEnabled;
        await this.localTracks.audioTrack.setEnabled(this.isAudioEnabled);
        this.log(this.isAudioEnabled ? "Mic Active" : "Mic Muted");
        console.log("Audio Enabled State:", this.isAudioEnabled);
        return !this.isAudioEnabled; // returns isMuted
    }

    async toggleVideo() {
        if (!this.localTracks.videoTrack) {
            this.log("Camera not ready", true);
            return this.isVideoEnabled === false; // correctly returns current isVideoOff state
        }
        this.isVideoEnabled = !this.isVideoEnabled;
        await this.localTracks.videoTrack.setEnabled(this.isVideoEnabled);
        this.log(this.isVideoEnabled ? "Camera On" : "Camera Off");
        console.log("Video Enabled State:", this.isVideoEnabled);
        return !this.isVideoEnabled; // returns isVideoOff
    }

    async forceSync() {
        this.client.remoteUsers.forEach(user => {
            this.handleUserPublished(user, "video");
            this.handleUserPublished(user, "audio");
        });
    }
}
