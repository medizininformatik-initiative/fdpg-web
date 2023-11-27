Vue.component('helpscout', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            collectionLoading: false,
            categoryLoading: false,
            data: { collectionlist: [], categorylist: [] },
            fields: [
                // create article
                { type: 'textarea', value: 'name', title: 'Name', task: ['createarticle'], required: false },
                { type: 'textarea', value: 'text', title: 'Text', task: ['createarticle'], required: false },
                // create category
                { type: 'textarea', value: 'cname', title: 'Category Name', task: ['createcategory'], required: true },
                { type: 'textarea', value: 'slug', title: 'Slug', task: ['createcategory'], required: true }


            ]

        }
    },



    methods: {

        CheckinDatabase: function(item, name) {
            var not_match = true;
            var saved_item = '{{' + item + '}}';

            if (!(typeof fieldData == 'undefined')) {
                var fieldaa = this.fielddata;
                for (i in fieldaa) {

                    if (fieldaa[i]) {

                        if (i == 'answerQ1' || i == 'answerQ2' || i == 'answerQ3') {

                            if (fieldaa[i].length == 2) {

                                if (fieldaa[i][1] == item) {
                                    not_match = false;
                                }
                            }

                        } else {

                            if (fieldaa[i] && (typeof fieldaa[i] === 'string')) {

                                if (fieldaa[i].includes(saved_item)) {
                                    not_match = false;
                                }
                            }
                        }

                    }
                }

            }

            return not_match;
        },


        selectedcategory: function(event) {
            this.fielddata.categoryid = event.target.value;

        },

        selectedcollection: function(event) {
            this.fielddata.collectionid = event.target.value;

        },


        getcollection: function() {
            this.collectionLoading = true;
            activePlatformId = this.fielddata.activePlatformId;


            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_collection',
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.collectionlist = response.data;
                this.sitelist = [...new Set(response.data)];
                this.collectionLoading = false;
            });
        },

        getcategory: function(event) {
            this.categoryLoading = true;
            activePlatformId = this.fielddata.activePlatformId;
            this.fielddata.collectionid = event.target.value;

            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_category',
                'collectionid': this.fielddata.collectionid
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.categorylist = response.data
                this.categoryLoading = false;
            });
        }
    },

    template: '#helpscout-action-template'
});