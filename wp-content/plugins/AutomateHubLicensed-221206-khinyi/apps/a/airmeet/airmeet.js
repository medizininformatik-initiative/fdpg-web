Vue.component('airmeet', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { timezoneList: [] },
        timezoneLoading: false,
        fields: [
                // create meet
              {type: 'textarea', value: 'eventName', title: 'Event Name', task: ['createmeet'], required: false},
              {type: 'textarea', value: 'shortDesc', title: 'Short Description', task: ['createmeet'], required: false},
              {type: 'textarea', value: 'longDesc', title: 'Long Description', task: ['createmeet'], required: false},
              {type: 'textarea', value: 'startTime', title: 'Start Date And Time', task: ['createmeet'], required: false},
              {type: 'textarea', value: 'endTime', title: 'End Date And Time', task: ['createmeet'], required: false},
            
          ]

      }
  },



methods: {

    CheckinDatabase:function(item, name){
        var not_match = true;
        var saved_item = '{{'+item+'}}';

        if(!(typeof fieldData == 'undefined')){
             var fieldaa = this.fielddata;
             for(i in fieldaa){

                if(fieldaa[i]){

                    if(i=='answerQ1' || i=='answerQ2' || i=='answerQ3'){

                        if(fieldaa[i].length==2){
                            
                            if(fieldaa[i][1]==item){
                                not_match = false;
                            }
                        }

                    }else{

                        if(fieldaa[i] &&  (typeof fieldaa[i]==='string')  ){

                            if(fieldaa[i].includes(saved_item)){
                                not_match = false;
                            }
                        }
                    }

                }
             }
            
        }

        return not_match;
    },


    selectedtimezone: function(event){
        this.fielddata.timezone = event.target.value;
        
    },

    gettimezone: function() {
        this.timezoneLoading = true;
        this.data.timezoneList = {
            0: { 'id':'Etc/GMT-12','name':'Etc/GMT-12'},
            1: { 'id':'Etc/GMT-11','name':'Etc/GMT-11'},
            2: { 'id':'Pacific/Midway','name':'Pacific/Midway'},
            3: { 'id':'America/Adak','name':'America/Adak'},
            4: { 'id':'America/Anchorage','name':'America/Anchorage'},
            5: { 'id':'Pacific/Gambier','name':'Pacific/Gambier'},
            6: { 'id':'America/Dawson_Creek','name':'America/Dawson_Creek'},
            7: { 'id':'America/Ensenada','name':'America/Ensenada'},
            8: { 'id':'America/Los_Angeles','name':'America/Los_Angeles'},
            9: { 'id':'America/Chihuahua','name':'America/Chihuahua'},
            10: { 'id':'America/Denver','name':'America/Denver'},
            11: { 'id':'America/Belize','name':'America/Belize'},
            12: { 'id':'America/Cancun','name':'America/Cancun'},
            13: { 'id':'America/Chicago','name':'America/Chicago'},
            14: { 'id':'Chile/EasterIsland','name':'Chile/EasterIsland'},
            15: { 'id':'America/Bogota','name':'America/Bogota'},
            16: { 'id':'America/Havana','name':'America/Havana'},
            17: { 'id':'America/New_York','name':'America/New_York'},
            18: { 'id':'America/Caracas','name':'America/Caracas'},
            19: { 'id':'America/Campo_Grande','name':'America/Campo_Grande'},
            20: { 'id':'America/Glace_Bay','name':'America/Glace_Bay'},
            21: { 'id':'America/Goose_Bay','name':'America/Goose_Bay'},
            22: { 'id':'America/Santiago','name':'America/Santiago'},
            23: { 'id':'America/La_Paz','name':'America/La_Paz'},
            24: { 'id':'America/Argentina/Buenos_Aires','name':'America/Argentina/Buenos_Aires'},
            25: { 'id':'America/Montevideo','name':'America/Montevideo'},
            26: { 'id':'America/Araguaina','name':'America/Araguaina'},
            27: { 'id':'America/Godthab','name':'America/Godthab'},
            28: { 'id':'America/Miquelon','name':'America/Miquelon'},
            29: { 'id':'America/Sao_Paulo','name':'America/Sao_Paulo'},
            30: { 'id':'America/St_Johns','name':'America/St_Johns'},
            31: { 'id':'America/Noronha','name':'America/Noronha'},
            32: { 'id':'Atlantic/Cape_Verde','name':'Atlantic/Cape_Verde'},
            33: { 'id':'Europe/Belfast','name':'Europe/Belfast'},
            34: { 'id':'Africa/Abidjan','name':'Africa/Abidjan'},
            35: { 'id':'Europe/Dublin','name':'Europe/Dublin'},
            36: { 'id':'Europe/Lisbon','name':'Europe/Lisbon'},
            37: { 'id':'Europe/London','name':'Europe/London'},
            38: { 'id':'Africa/Algiers','name':'Africa/Algiers'},
            39: { 'id':'Africa/Windhoek','name':'Africa/Windhoek'},
            40: { 'id':'Atlantic/Azores','name':'Atlantic/Azores'},
            41: { 'id':'Atlantic/Stanley','name':'Atlantic/Stanley'},
            42: { 'id':'Europe/Amsterdam','name':'Europe/Amsterdam'},
            43: { 'id':'Europe/Belgrade','name':'Europe/Belgrade'},
            44: { 'id':'Europe/Brussels','name':'Europe/Brussels'},
            45: { 'id':'Africa/Cairo','name':'Africa/Cairo'},
            46: { 'id':'Africa/Blantyre','name':'Africa/Blantyre'},
            47: { 'id':'Asia/Beirut','name':'Asia/Beirut'},
            48: { 'id':'Asia/Damascus','name':'Asia/Damascus'},
            49: { 'id':'Asia/Gaza','name':'Asia/Gaza'},
            50: { 'id':'Asia/Jerusalem','name':'Asia/Jerusalem'},
            51: { 'id':'Africa/Addis_Ababa','name':'Africa/Addis_Ababa'},
            52: { 'id':'Asia/Riyadh89','name':'Asia/Riyadh89'},
            53: { 'id':'Europe/Minsk','name':'Europe/Minsk'},
            54: { 'id':'Asia/Tehran','name':'Asia/Tehran'},
            55: { 'id':'Asia/Dubai','name':'Asia/Dubai'},
            56: { 'id':'Asia/Yerevan','name':'Asia/Yerevan'},
            57: { 'id':'Europe/Moscow','name':'Europe/Moscow'},
            58: { 'id':'Asia/Kabul','name':'Asia/Kabul'},
            59: { 'id':'Asia/Tashkent','name':'Asia/Tashkent'},
            60: { 'id':'Asia/Kolkata','name':'Asia/Kolkata'},
            61: { 'id':'Asia/Katmandu','name':'Asia/Katmandu'},
            62: { 'id':'Asia/Dhaka','name':'Asia/Dhaka'},
            63: { 'id':'Asia/Yekaterinburg','name':'Asia/Yekaterinburg'},
            64: { 'id':'Asia/Rangoon','name':'Asia/Rangoon'},
            65: { 'id':'Asia/Bangkok','name':'Asia/Bangkok'},
            66: { 'id':'Asia/Novosibirsk','name':'Asia/Novosibirsk'},
            67: { 'id':'Etc/GMT+8','name':'Etc/GMT+8'},
            68: { 'id':'Asia/Hong_Kong','name':'Asia/Hong_Kong'},
            69: { 'id':'Asia/Krasnoyarsk','name':'Asia/Krasnoyarsk'},
            70: { 'id':'Australia/Perth','name':'Australia/Perth'},
            71: { 'id':'Australia/Eucla','name':'Australia/Eucla'},
            72: { 'id':'Asia/Irkutsk','name':'Asia/Irkutsk'},
            73: { 'id':'Asia/Seoul','name':'Asia/Seoul'},
            74: { 'id':'Asia/Tokyo','name':'Asia/Tokyo'},
            75: { 'id':'Australia/Adelaide','name':'Australia/Adelaide'},
            76: { 'id':'Australia/Darwin','name':'Australia/Darwin'},
            77: { 'id':'Pacific/Marquesas','name':'Pacific/Marquesas'},
            78: { 'id':'Etc/GMT+10','name':'Etc/GMT+10'},
            79: { 'id':'Australia/Brisbane','name':'Australia/Brisbane'},
            80: { 'id':'Australia/Hobart','name':'Australia/Hobart'},
            81: { 'id':'Asia/Yakutsk','name':'Asia/Yakutsk'},
            82: { 'id':'Australia/Lord_Howe','name':'Australia/Lord_Howe'},
            83: { 'id':'Asia/Vladivostok','name':'Asia/Vladivostok'},
            84: { 'id':'Pacific/Norfolk','name':'Pacific/Norfolk'},
            85: { 'id':'Etc/GMT+12','name':'Etc/GMT+12'},
            86: { 'id':'Asia/Anadyr','name':'Asia/Anadyr'},
            87: { 'id':'Asia/Magadan','name':'Asia/Magadan'},
            88: { 'id':'Pacific/Auckland','name':'Pacific/Auckland'},
            89: { 'id':'Pacific/Chatham','name':'Pacific/Chatham'},
            90: { 'id':'Pacific/Tongatapu','name':'Pacific/Tongatapu'},
            91: { 'id':'Pacific/Kiritimati','name':'Pacific/Kiritimati'}
        };
        this.timezoneLoading = false;
    }
},

 template: '#airmeet-action-template'
});