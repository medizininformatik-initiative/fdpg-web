// Vue.component('googlesheets', {
//     props: ["trigger", "action", "fielddata"],
//     data: function () {
//             listLoading: false,
//             worksheetLoading: false,
//             fields: []
//         }
//     },
//     methods: {
//         getWorksheets: function() {
//             if(!this.fielddata.spreadsheetId) {
//                 return;
//             }
//             this.fielddata.worksheetList = [];
//             this.fielddata.worksheetId = '';
//             this.fields = [];
//             var that = this;
//             this.worksheetLoading = true;
//             var listData = {
//                 'action': 'awp_googlesheets_get_worksheets',
//                 '_nonce': awp.nonce,
//                 'spreadsheetId': this.fielddata.spreadsheetId,
//                 'task': this.action.task
//             };
//             jQuery.post( ajaxurl, listData, function( response ) {
//                 that.fielddata.worksheetList = response.data;
//                 that.worksheetLoading = false;
//             });
//         },
//         getHeaders: function() {
//             if(this.fielddata.worksheetId == 0 || this.fielddata.worksheetId) {
//                 this.fields = [];
//                 var that = this;
//                 this.worksheetLoading = true;
//                 this.fielddata.worksheetName = this.fielddata.worksheetList[this.fielddata.worksheetId];
//                 var requestData = {
//                     'action': 'awp_googlesheets_get_headers',
//                     '_nonce': awp.nonce,
//                     'spreadsheetId': this.fielddata.spreadsheetId,
//                     'worksheetName': this.fielddata.worksheetName,
//                     'task': this.action.task
//                 };
//                 jQuery.post( ajaxurl, requestData, function( response ) {
//                     if(response.success) {
//                         if(response.data) {
//                             for(var key in response.data) {
//                                 that.fielddata[key] = '';
//                                 that.fields.push({value: key, title: response.data[key], task: ['add_row'], required: false});
//                             }
//                         }
//                     }
//                     that.worksheetLoading = false;
//                 });
//             }
//         }
//     },
//     created: function() {
//     },
//     mounted: function() {
//         var that = this;
//         if (typeof this.fielddata.spreadsheetId == 'undefined') {
//             this.fielddata.spreadsheetId = '';
//         }
//         if (typeof this.fielddata.worksheetId == 'undefined') {
//             this.fielddata.worksheetId = '';
//         }
//         if(typeof this.fielddata.worksheetName == 'undefined') {
//             this.fielddata.worksheetName = '';
//         }
//         this.listLoading = true;
//         var listRequestData = {
//             'action': 'awp_get_spreadsheet_list',
//             '_nonce': awp.nonce
//         };
//         jQuery.post( ajaxurl, listRequestData, function( response ) {
//             that.fielddata.spreadsheetList = response.data;
//             that.listLoading = false;
//         });
//         if(this.fielddata.spreadsheetId && this.fielddata.worksheetName ) {
//             var that = this;
//             this.worksheetLoading = true;
//             var requestData = {
//                 'action': 'awp_googlesheets_get_headers',
//                 '_nonce': awp.nonce,
//                 'spreadsheetId': this.fielddata.spreadsheetId,
//                 'worksheetName': this.fielddata.worksheetName,
//                 'task': this.action.task
//             };
//             jQuery.post( ajaxurl, requestData, function( response ) {
//                 if(response.success) {
//                     if(response.data) {
//                         for(var key in response.data) {
//                             that.fields.push({value: key, title: response.data[key], task: ['add_row'], required: false});
//                         }
//                     }
//                 }
//                 that.worksheetLoading = false;
//             });
//         }
//     },
//     watch: {},
//     template: '#googlesheets-action-template'
// });