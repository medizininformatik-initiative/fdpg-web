Vue.component('googledrive', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        let fileTypeValue = this.fielddata.list;
        return {
            listLoading: false,
            fields: [
                {type: 'textarea', value: 'familyname', title: 'Family Name', task: ['createcontact'], required: false}, 
                {type: 'textarea', value: 'givenName', title: 'Given Name', task: ['createcontact'], required: false}, 
                {type: 'textarea', value: 'middleName', title: 'Middle Name', task: ['createcontact'], required: false}, 
                {type: 'textarea', value: 'country', title: 'Country', task: ['createcontact'], required: false}, 
                {type: 'textarea', value: 'city', title: 'City', task: ['createcontact'], required: false}, 
                {type: 'textarea', value: 'region', title: 'Region', task: ['createcontact'], required: false}, 
                {type: 'textarea', value: 'streetAddress', title: 'Street Address', task: ['createcontact'], required: false}, 
                {type: 'textarea', value: 'postalCode', title: 'Poster Code', task: ['createcontact'], required: false}, 
                {type: 'textarea', value: 'phoneNumber', title: 'Phone Number', task: ['createcontact'], required: false}, 
            ]
        }
    },
    methods: {

        CheckinDatabase: function (item, name) {

            let not_match = true;

            let saved_item = '{{' + item + '}}';
            if (!(typeof fieldData == 'undefined')) {
                let fieldaa = this.fielddata;
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

        getGoogleDriveList: function () {
            this.listLoading = false;

            let listRequestData = {
                'action': 'awp_get_googledrive_list',
                'platformid': this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post(ajaxurl, listRequestData, function (response) {
                this.fielddata.list = response.data;
                this.listLoading = false;
            });
        },

        getFileTypeList: function (event) {
            this.fielddata.list = event.target.value;
        }

    },
    mounted: function () {

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.title == 'undefined') {
            this.fielddata.title = '';
        }


        this.fielddata.fileList={'.txt':'.TXT','.doc':'.DOC','.docx':'.DOCX','.html':'.HTML','.pdf':'.PDF','.xls':'.XLS','.xlsx':'.XLSX','.ppt':'.PPT','.pptx':'.PPTX'}

        this.listLoading = false;

        let listRequestData = {
            'action': 'awp_get_googledrive_list',
            '_nonce': awp.nonce
        };

        jQuery.post(ajaxurl, listRequestData, function (response) {
            this.fielddata.list = response.data;
            this.listLoading = false;
        });
    },
    template: '#googledrive-action-template'
});