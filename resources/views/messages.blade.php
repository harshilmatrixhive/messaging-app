<x-app-layout>
    <x-slot name="styles">
        <style>
            .chat-area {
                min-height: calc(100vh - 18rem);
            }

            .messages {
                max-height: 450px;
            }

            .sidebar {
                max-height: 590px;
            }

            .active {
                background-color: cornflowerblue;
            }

            ::-webkit-scrollbar {
                width: 5px;
                height: 5px;
            }

            ::-webkit-scrollbar-track {
                -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
                -webkit-border-radius: 10px;
                border-radius: 10px;
            }

            ::-webkit-scrollbar-thumb {
                -webkit-border-radius: 10px;
                border-radius: 10px;
                background: rgba(255, 255, 255, 0.3);
                -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
            }

            ::-webkit-scrollbar-thumb:window-inactive {
                background: rgba(255, 255, 255, 0.3);
            }
        </style>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12" id="app">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" v-if="is_loaded">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="main flex-1 flex flex-col">
                        <div class="flex-1 flex h-full">
                            <div
                                class="sidebar hidden lg:flex w-1/3 flex-2 flex-col pr-3 border-solid border-r-2 border-gray-400 mr-4">
                                <div class="flex-1 h-full overflow-auto px-2">
                                    @foreach ($users as $user)
                                        <div class="entry cursor-pointer transform bg-white mb-4 rounded p-4 flex shadow-md items-center" :class="({{$user->id}} == to_id)?'active':''"
                                            @click='viewMessages("{{ $user->id }}","{{ $user->name }}")'>
                                            <div class="flex-2">
                                                <div class="w-12 h-12 relative">
                                                    <img class="w-12 h-12 rounded-full mx-auto"
                                                        src="{{ asset('images/user-default.png') }}"
                                                        alt="chat-user" />
                                                </div>
                                            </div>
                                            <div class="flex-1 px-2">
                                                <div class="w-32">
                                                    <span class="text-gray-800">{{ $user->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="chat-area flex-1 flex flex-col" v-if="to_name != ''">
                                <div class="flex-3">
                                    <h2 class="text-xl py-1 mb-8 border-b-2 border-gray-200"><span>Chatting with
                                            <b>@{{ to_name }}</b></span></h2>
                                </div>
                                <div class="messages flex-1 overflow-auto">
                                    <div v-if="messages.length == 0">No messages found</div>
                                    <span v-for="message in messages">
                                        <div class="message me mb-4 flex text-right" v-if="message.to_id == to_id">
                                            <div class="flex-1 px-2">
                                                <div class="flex items-center justify-end">
                                                    <div
                                                        class="inline-block bg-blue-600 rounded-full p-2 px-6 text-white">
                                                        <span>@{{ message.message }}</span>
                                                    </div>
                                                    <img class="w-5" src="{{ asset('images/delete.png') }}"
                                                        alt="delete-message" style="cursor: pointer;"
                                                        @click="deleteMessage(message.id)" />
                                                </div>
                                                <div class="pr-4"><small
                                                        class="text-gray-500">@{{ message.created_at }}</small></div>
                                            </div>
                                        </div>
                                        <div class="message mb-4 flex" v-else>
                                            <div class="flex-2">
                                                <div class="w-12 h-12 relative">
                                                    <img class="w-12 h-12 rounded-full mx-auto"
                                                        src="{{ asset('images/user-default.png') }}"
                                                        alt="chat-user" />
                                                </div>
                                            </div>
                                            <div class="flex-1 px-2">
                                                <div
                                                    class="inline-block bg-gray-300 rounded-full p-2 px-6 text-gray-700">
                                                    <span>@{{ message.message }}</span>
                                                </div>
                                                <div class="pl-4"><small
                                                        class="text-gray-500">@{{ message.created_at }}</small></div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                <div class="flex-2 pt-4 ">
                                    <div class="write bg-white shadow flex rounded-lg">

                                        <div class="flex-1">
                                            <textarea name="message" class="w-full block outline-none py-4 px-4 bg-transparent message-content" rows="1"
                                                placeholder="Type a message..." autofocus v-model="message"></textarea>
                                        </div>
                                        <div class="flex-2 w-25 p-2 flex content-center items-center">
                                            <div class="flex-1">
                                                <button
                                                    class="bg-blue-400 w-10 h-10 rounded-full inline-block send-messsage"
                                                    @click="sendMessage()">
                                                    <span class="inline-block align-text-bottom">
                                                        <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                            class="w-4 h-4 text-white">
                                                            <path d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            const {
                createApp
            } = Vue
            const base_url = '{{ url('/') }}';

            createApp({
                data() {
                    return {
                        users: {!! $users !!},
                        is_loaded: false,
                        to_id: '',
                        to_name: '',
                        message: '',
                        messages: []
                    }
                },
                methods: {
                    viewMessages(id, name) {
                        if (id != '') {
                            this.to_id = id;
                            this.to_name = name;

                            let url = base_url + "/messages/" + id;
                            let method = "GET";
                            let _this = this;

                            commonAjaxCall(url, method, null, function(result) {
                                if (result.status) {
                                    _this.messages = result.data.map(function(task) {
                                        task.created_at = moment.utc(task.created_at).local().fromNow();
                                        return task;
                                    });
                                    $('.messages').animate({ scrollTop: $(document).height() }, 1000);
                                }
                            });
                        }
                    },
                    sendMessage() {
                        if (this.message != '') {
                            let url = base_url + "/messages";
                            let method = "POST";
                            let data = {
                                'to_id': this.to_id,
                                'message': this.message
                            }
                            let _this = this;

                            commonAjaxCall(url, method, data, function(result) {
                                if (result.status) {
                                    _this.viewMessages(_this.to_id, _this.to_name);
                                    _this.message = '';
                                }
                            });
                        }
                    },
                    checkNewMessages() {
                        let _this = this;
                        const interval = setInterval(function() {
                            _this.viewMessages(_this.to_id, _this.to_name);
                        }, 1000);

                        // clearInterval(interval);
                    },
                    deleteMessage(id) {
                        let url = base_url + "/messages/" + id;
                        let method = "DELETE";
                        let _this = this;

                        commonAjaxCall(url, method, null, function(result) {
                            if (result.status) {
                                _this.viewMessages(_this.to_id, _this.to_name);
                            }
                        });
                    }
                },
                mounted() {
                    this.checkNewMessages();
                    if (this.users.length > 0) {
                        this.viewMessages(this.users[0].id, this.users[0].name);
                    }
                    this.is_loaded = true;
                }
            }).mount('#app');
        </script>
    </x-slot>
</x-app-layout>
