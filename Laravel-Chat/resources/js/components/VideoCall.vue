<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="col grid justify-items-center">
                    <div class="btn-group" role="group" v-for="user in allusers" :key="user.id">
                        <button type="button" class="text-white bg-sky-500/100 rounded-none p-2 mr-2"
                            v-if="chat_user.id == user.id" @click="placeVideoCall(user.id, user.name)">
                            <span class=" flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z" />
                                </svg>
                                Call {{ user.name }}
                                <span class="badge badge-light">
                                    {{ getUserOnlineStatus(user.id) }}
                                </span>
                            </span>

                        </button>
                    </div>
                </div>
            </div>
            <!--Placing Video Call-->
            <div class="root">
                <div>
                    <div class="row mt-5 root" id="video-row">
                        <div class="col-12 video-container " v-if="callPlaced">
                            <video id="video_remote" ref="userVideo" muted playsinline autoplay class="cursor-pointer"
                                :class="isFocusMyself === true ? 'user-video' : 'partner-video' "
                                @click="toggleCameraArea" />
                            <video id="video_local" ref="partnerVideo" playsinline autoplay class="cursor-pointer"
                                :class="isFocusMyself === true ? 'partner-video1' : 'user-video1'"
                                @click="toggleCameraArea" v-if="videoCallParams.callAccepted" />
                            <div class="partner-video" v-else>
                                <div v-if="callPartner" class="column items-center q-pt-xl">
                                    <div class="col q-gutter-y-md text-center">
                                        <p class="q-pt-md">
                                            <strong>{{ callPartner }}</strong>
                                        </p>
                                        <p>calling...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="callbtns" v-if="showOptions">
                    <div class="action-btns flex justify-center">
                        <button type="button" class="btn btn-info text-white bg-sky-500/100 rounded-none p-2"
                            @click="toggleMuteAudio">
                            {{ mutedAudio ? "Unmute" : "Mute" }}
                        </button>
                        <button type="button" class="btn btn-primary mx-4 text-white bg-green-500/100 rounded-none p-2"
                            @click="toggleMuteVideo">
                            {{ mutedVideo ? "ShowVideo" : "HideVideo" }}
                        </button>
                        <button type="button" class="btn btn-danger text-white bg-red-500/100 rounded-none p-2"
                            @click="endCall">
                            EndCall
                        </button>
                    </div>
                </div>
                <hr>
                <hr>
                <div id="log">
                    <div class="row" v-if="incomingCallDialog">
                        <div class="col grid justify-items-center">
                            <p>
                                Incoming Call From <strong>{{ callerDetails.name }}</strong>
                            </p>
                            <div class="btn-group flex justify-center" role="group">
                                <button type="button" class="btn btn-danger text-white bg-red-500/100 rounded-none p-2"
                                    data-dismiss="modal" @click="declineCall">
                                    Decline
                                </button>
                                <button type="button"
                                    class="btn btn-success ml-5 text-white bg-sky-500/100 rounded-none p-2"
                                    @click="acceptCall">
                                    Accept
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Placing Video Call-->




            <!--Placing Video Call-->

            <!-- End of Placing Video Call  -->
            <!-- Incoming Call  -->

            <!-- End of Incoming Call  -->
        </div>
    </div>
</template>
<script>
    import Peer from "simple-peer";
    import {
        getPermissions
    } from "../helpers";
    export default {

        props: [
            "chat_user",
            "auth_user",
            "allusers",
            "authuserid",
            "turn_url",
            "turn_username",
            "turn_credential",
        ],

        data() {
            return {
                isFocusMyself: true,
                callPlaced: false,
                callPartner: null,
                mutedAudio: false,
                mutedVideo: false,
                videoCallParams: {
                    users: [],
                    stream: null,
                    receivingCall: false,
                    caller: null,
                    callerSignal: null,
                    callAccepted: false,
                    channel: null,
                    peer1: null,
                    peer2: null,
                },
                online: false,
                showOptions:false,
            };
        },

        mounted() {
            this.initializeChannel(); // this initializes laravel echo
            this.initializeCallListeners(); // subscribes to video presence channel and listens to video events
        },
        computed: {
            incomingCallDialog() {
                if (
                    this.videoCallParams.receivingCall &&
                    this.videoCallParams.caller !== this.authuserid
                ) {
                    return true;
                }
                return false;
            },

            callerDetails() {
                if (
                    this.videoCallParams.caller &&
                    this.videoCallParams.caller !== this.authuserid
                ) {
                    const incomingCaller = this.allusers.filter(
                        (user) => user.id === this.videoCallParams.caller
                    );

                    return {
                        id: this.videoCallParams.caller,
                        name: `${incomingCaller[0].name}`,
                    };
                }
                return null;
            },
        },
        methods: {
            initializeChannel() {
                this.videoCallParams.channel = window.Echo.join("presence-video-channel");
            },

            getMediaPermission() {
                return getPermissions()
                    .then((stream) => {
                        this.videoCallParams.stream = stream;
                        if (this.$refs.userVideo) {
                            this.$refs.userVideo.srcObject = stream;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            initializeCallListeners() {
                this.videoCallParams.channel.here((users) => {
                    this.videoCallParams.users = users;
                });

                this.videoCallParams.channel.joining((user) => {
                    // check user availability
                    const joiningUserIndex = this.videoCallParams.users.findIndex(
                        (data) => data.id === user.id
                    );
                    if (joiningUserIndex < 0) {
                        this.videoCallParams.users.push(user);
                    }
                });

                this.videoCallParams.channel.leaving((user) => {
                    const leavingUserIndex = this.videoCallParams.users.findIndex(
                        (data) => data.id === user.id
                    );
                    this.videoCallParams.users.splice(leavingUserIndex, 1);
                });
                // listen to incomming call
                this.videoCallParams.channel.listen("StartVideoChat", ({
                    data
                }) => {
                    if (data.type === "incomingCall") {
                        // add a new line to the sdp to take care of error
                        const updatedSignal = {
                            ...data.signalData,
                            sdp: `${data.signalData.sdp}\n`,
                        };

                        this.videoCallParams.receivingCall = true;
                        this.videoCallParams.caller = data.from;
                        this.videoCallParams.callerSignal = updatedSignal;
                    }
                });
            },
            async placeVideoCall(id, name) {
                this.callPlaced = true;
                this.showOptions = true;
                this.callPartner = name;
                await this.getMediaPermission();
                this.videoCallParams.peer1 = new Peer({
                    initiator: true,
                    trickle: false,
                    stream: this.videoCallParams.stream,
                    config: {
                        iceServers: [{
                            urls: this.turn_url,
                            username: this.turn_username,
                            credential: this.turn_credential,
                        }, ],
                    },
                });

                this.videoCallParams.peer1.on("signal", (data) => {
                    // send user call signal
                    axios
                        .post("/video/call-user", {
                            user_to_call: id,
                            signal_data: data,
                            from: this.authuserid,
                        })
                        .then(() => {})
                        .catch((error) => {
                            console.log(error);
                        });
                });

                this.videoCallParams.peer1.on("stream", (stream) => {
                    console.log("call streaming");
                    if (this.$refs.partnerVideo) {
                        this.$refs.partnerVideo.srcObject = stream;
                    }
                });

                this.videoCallParams.peer1.on("connect", () => {
                    console.log("peer connected");
                });

                this.videoCallParams.peer1.on("error", (err) => {
                    console.log(err);
                });

                this.videoCallParams.peer1.on("close", () => {
                    console.log("call closed caller");
                });

                this.videoCallParams.channel.listen("StartVideoChat", ({
                    data
                }) => {
                    if (data.type === "callAccepted") {
                        if (data.signal.renegotiate) {
                            console.log("renegotating");
                        }
                        if (data.signal.sdp) {
                            this.videoCallParams.callAccepted = true;
                            const updatedSignal = {
                                ...data.signal,
                                sdp: `${data.signal.sdp}\n`,
                            };
                            this.videoCallParams.peer1.signal(updatedSignal);
                        }
                    }
                });
            },

            async acceptCall() {
                this.callPlaced = true;
                this.showOptions = true;
                this.videoCallParams.callAccepted = true;
                await this.getMediaPermission();
                this.videoCallParams.peer2 = new Peer({
                    initiator: false,
                    trickle: false,
                    stream: this.videoCallParams.stream,
                    config: {
                        iceServers: [{
                            urls: this.turn_url,
                            username: this.turn_username,
                            credential: this.turn_credential,
                        }, ],
                    },
                });
                this.videoCallParams.receivingCall = false;
                this.videoCallParams.peer2.on("signal", (data) => {
                    axios
                        .post("/video/accept-call", {
                            signal: data,
                            to: this.videoCallParams.caller,
                        })
                        .then(() => {})
                        .catch((error) => {
                            console.log(error);
                        });
                });

                this.videoCallParams.peer2.on("stream", (stream) => {
                    this.videoCallParams.callAccepted = true;
                    this.$refs.partnerVideo.srcObject = stream;
                });

                this.videoCallParams.peer2.on("connect", () => {
                    console.log("peer connected");
                    this.videoCallParams.callAccepted = true;
                });

                this.videoCallParams.peer2.on("error", (err) => {
                    console.log(err);
                });

                this.videoCallParams.peer2.on("close", () => {
                    console.log("call closed accepter");
                });

                this.videoCallParams.peer2.signal(this.videoCallParams.callerSignal);
            },
            toggleCameraArea() {
                if (this.videoCallParams.callAccepted) {
                    this.isFocusMyself = !this.isFocusMyself;
                }
            },
            getUserOnlineStatus(id) {
                const onlineUserIndex = this.videoCallParams.users.findIndex(
                    (data) => data.id === id
                );
                if (onlineUserIndex < 0) {
                    this.online = true
                    return " Offline";
                }
                return " Online";
            },
            declineCall() {
                this.videoCallParams.receivingCall = false;
            },

            toggleMuteAudio() {
                if (this.mutedAudio) {
                    this.$refs.userVideo.srcObject.getAudioTracks()[0].enabled = true;
                    this.mutedAudio = false;
                } else {
                    this.$refs.userVideo.srcObject.getAudioTracks()[0].enabled = false;
                    this.mutedAudio = true;
                }
            },

            toggleMuteVideo() {
                if (this.mutedVideo) {
                    this.$refs.userVideo.srcObject.getVideoTracks()[0].enabled = true;
                    this.mutedVideo = false;
                } else {
                    this.$refs.userVideo.srcObject.getVideoTracks()[0].enabled = false;
                    this.mutedVideo = true;
                }
            },

            stopStreamedVideo(videoElem) {
                const stream = videoElem.srcObject;
                const tracks = stream.getTracks();
                tracks.forEach((track) => {
                    track.stop();
                });
                videoElem.srcObject = null;
            },
            endCall() {
                // if video or audio is muted, enable it so that the stopStreamedVideo method will work
                if (!this.mutedVideo) this.toggleMuteVideo();
                if (!this.mutedAudio) this.toggleMuteAudio();
                this.stopStreamedVideo(this.$refs.userVideo);
                if (this.authuserid === this.videoCallParams.caller) {
                    this.videoCallParams.peer1.destroy();
                } else {
                    this.videoCallParams.peer2.destroy();
                }
                this.videoCallParams.channel.pusher.channels.channels[
                    "presence-presence-video-channel"
                ].disconnect();

                setTimeout(() => {
                    this.callPlaced = false;
                }, 3000);
            },
        },
    }

</script>
<style scoped>
    /* #caller-video{
    display: block;
    margin: 0 auto;
    width:20%;
}
#receiver-video{
    display: block;
    margin: 0 auto;
    width: 50%;
} */

    /* .user-video {
        display: block;
        margin: 0 auto;
        width: 20%;
    }

    .partner-video {
        display: block;
        margin: 0 auto;
        width: 50%;
    }

    .user-video1 {
        display: block;
        margin: 0 auto;
        width: 50%;
    }

    .partner-video1 {
        display: block;
        margin: 0 auto;
        width: 20%;
    } */



    video {
        background: #000;
        float: left;
        width: 45%;
        border: 1px solid #f00;
        margin: 5px;
        height: 300px !important;
    }

    hr {
        clear: both;
    }

    .hide {
        display: none;
    }

    .root .showincall {
        display: none;
    }

    .root .hideincall {
        display: block;
    }

    .root.incall .showincall {
        display: block;
    }

    .root.incall .hideincall {
        display: none;
    }

    .callbtns button {
        float: left;
        margin: 5px;
        margin-top: 0px;
        margin-bottom: 10px;
    }

    #log {
        color: #000
    }

    .status {
        color: #272;
        font-weight: bold;
    }

    .note {
        background: #eee;
        padding: 1px 10px;
        margin: 5px;
        border-radius: 5px;
        text-align: justify;
    }

</style>
