<template>
    <app-layout title="Dashboard">
        <template #header>
            <div class="flex flex-row py-4 px-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Advertising Campaigns
                </h2>

                <div class="ml-auto">
                    <jet-button @click="openNewCampaignPopup()">
                        Add New Campaign
                    </jet-button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <campaign-table :campaigns="campaigns" @updateCampaign="updateCampaignData" />
                </div>
            </div>
        </div>
    </app-layout>

    <jet-dialog-modal :show="addCampaignDialog" @close="addCampaignDialog = false">
        <template #title>
            <template v-if="isNewCampaign">
                Create Advertising Campaign
            </template>
            <template v-else>
                Update Advertising Campaign
            </template>
        </template>

        <template #content>
            <div class="mt-5">
                <jet-label for="name" value="Campaign Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" ref="name" />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>
            <div class="mt-5">
                <jet-label for="date_from" value="Date From" />
                <jet-input id="date_from" type="date" class="mt-1 block w-full" v-model="form.date_from" ref="date_from" />
                <jet-input-error :message="form.errors.date_from" class="mt-2" />
            </div>
            <div class="mt-5">
                <jet-label for="date_to" value="Date Until" />
                <jet-input id="date_to" type="date" class="mt-1 block w-full" v-model="form.date_to" ref="date_to" />
                <jet-input-error :message="form.errors.date_to" class="mt-2" />
            </div>
            <div class="mt-5">
                <jet-label for="total_budget" value="Total Budget (USD)" />
                <jet-input id="total_budget" type="number" class="mt-1 block w-full" v-model="form.total_budget" ref="total_budget" />
                <jet-input-error :message="form.errors.total_budget" class="mt-2" />
            </div>
            <div class="mt-5">
                <jet-label for="daily_budget" value="Total Budget (USD)" />
                <jet-input id="daily_budget" type="number" class="mt-1 block w-full" v-model="form.daily_budget" ref="daily_budget" />
                <jet-input-error :message="form.errors.daily_budget" class="mt-2" />
            </div>
            <div class="mt-5">
                <jet-label for="image" value="Image" />
                <input type="file" class="mt-1 block w-full" ref="image" @change="showImagePreview">
                <jet-input-error :message="form.errors.image" class="mt-2" />

                <div class="mt-2" v-show="imagePreview">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          :style="'background-image: url(\'' + imagePreview + '\');'">
                    </span>
                </div>
            </div>
        </template>

        <template #footer>
            <jet-secondary-button @click="addCampaignDialog = false">
                Cancel
            </jet-secondary-button>

            <jet-button class="ml-2" @click="saveCampaign" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                <template v-if="isNewCampaign">
                    Add
                </template>
                <template v-else>
                    Update
                </template>
            </jet-button>
        </template>
    </jet-dialog-modal>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetDialogModal from "@/Jetstream/DialogModal";
    import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
    import JetSecondaryButton from "@/Jetstream/SecondaryButton";
    import JetLabel from "@/Jetstream/Label";
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue';
    import CampaignTable from "./Campaign/CampaignTable";


    export default defineComponent({
        components: {
            AppLayout,
            JetButton,
            JetDialogModal,
            JetConfirmationModal,
            JetSecondaryButton,
            JetLabel,
            JetInput,
            JetInputError,
            CampaignTable,
        },

        data() {
            return {
                campaigns: [],
                addCampaignDialog: false,
                imagePreview: null,
                isNewCampaign: true,
                defaultForm: {
                    _method: 'POST',
                    id: '',
                    name: '',
                    date_from: '',
                    date_to: '',
                    total_budget: '',
                    daily_budget: '',
                    image: null
                },
                form: null,
            }
        },

        methods: {
            openNewCampaignPopup() {
                this.form = this.$inertia.form(this.defaultForm);
                this.isNewCampaign = true;
                this.addCampaignDialog = true
            },

            getCampaigns() {
                const _this = this;
                axios.get(this.route('campaign.listing'))
                    .then(function (response) {
                        let _data = response.data;

                        if (_data.status === true) {
                            _this.campaigns = _data.data;
                        }

                    }).catch(function (error) {
                        // handle error
                        console.log(error);
                    });
            },

            saveCampaign(index) {
                index = index || null;

                const insert = this.isNewCampaign;
                const _this = this;

                this.form.post(route('campaign.save'), {
                    // errorBag: 'updateProfileInformation',
                    preserveScroll: true,
                    onSuccess: () => (this.clearImageFileInput()),
                });

                // axios.post(this.route('campaign.save'), this.form,{
                //     headers: {
                //         Authorization: 'Bearer ' + this.$page.props.user.auth_token
                //     }
                // }).then(function (response) {
                //     let _data = response.data;
                //
                //     if (_data.status === true) {
                //         if (insert) {
                //             _this.channels.push(_data.data);
                //         } else {
                //             _this.channels[index] = _data.data;
                //         }
                //     }
                //
                // }).catch(function (error) {
                //     // handle error
                //     console.log(error);
                // });
            },

            updateCampaignData(index, campaign) {
                this.isNewCampaign = false;

                campaign['_method'] = 'POST';
                this.form = this.$inertia.form(campaign);

                this.addCampaignDialog = true;
            },

            showImagePreview() {
                const image = this.$refs.image.files[0];
                if (! image) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                    this.form.image = e.target.result;
                };
                reader.readAsDataURL(image);
            },

            clearImageFileInput() {
                if (this.$refs.image?.value) {
                    this.$refs.image.value = null;
                }
            },
        },

        async created() {
            await this.getCampaigns();
        }
    })
</script>
