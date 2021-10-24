<template>
    <div class="p-4">
        <table class="table-auto w-full text-center">
            <tr class="bg-gray-100">
                <th class="p-2">&nbsp;</th>
                <th>Name</th>
                <th>Date From</th>
                <th>Date Until</th>
                <th>Total Budget</th>
                <th>Daily Budget</th>
                <th></th>
            </tr>

            <single-item v-if="campaigns.length > 0" v-for="(campaign, key) in campaigns" :index="key" :campaign="campaign" @updateCampaign="updateCampaign"></single-item>
            <tr v-else>
                <td colspan="7" class="text-gray-700 text-center p-2">
                    No record found
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
import {defineComponent} from "vue";
import SingleItem from "./SingleItem";

export default defineComponent({
    name: "CampaignTable",

    components: {
        SingleItem
    },

    props: ['campaigns'],

    emits: ['update-campaign'],

    methods: {
        updateCampaign(key, campaign) {
            this.$emit('update-campaign', key, campaign);
        }
    }
})
</script>

<style scoped>

</style>
