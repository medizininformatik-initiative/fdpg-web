Vue.component('clickup', {
    props: ["trigger", "action", "fielddata"],
    data: function() {

        return {
            data: { teamList: [], colourList: [], adminStatusList: [], tagEditStatusList: [], timeSpentStatusList: [], timeEstimateStatusList: [], createViewStatusList: [], spaceList: [], folderList: [], priorityList: [], listList: [], taskpriorityList: [] },
            teamLoading: false,
            colourLoading: false,
            spaceLoading: false,
            folderLoading: false,
            priorityLoading: false,
            listLoading: false,
            fields: [
                // create goals
                { type: 'textarea', value: 'name', title: 'Name', task: ['creategoal'], required: true },
                { type: 'textarea', value: 'description', title: 'Description', task: ['creategoal'], required: true },
                { type: 'textarea', value: 'due_date', title: 'Due Date', task: ['creategoal'], required: true },
                // create space
                { type: 'textarea', value: 'spacename', title: 'Space Name', task: ['createspace'], required: true },
                // Invite User
                { type: 'textarea', value: 'email', title: 'Email', task: ['inviteuser'], required: true },
                // Invite Guest
                { type: 'textarea', value: 'guestemail', title: 'Email', task: ['inviteguest'], required: true },
                // Create Folder
                { type: 'textarea', value: 'foldername', title: 'Space Name', task: ['createfolder'], required: true },
                // Create list
                { type: 'textarea', value: 'listname', title: 'Name', task: ['createlist'], required: true },
                { type: 'textarea', value: 'content', title: 'Content', task: ['createlist'], required: true },
                { type: 'textarea', value: 'due_date', title: 'Due Date', task: ['createlist'], required: true },
                // Create task
                { type: 'textarea', value: 'taskname', title: 'Task Name', task: ['createtask'], required: true },
                { type: 'textarea', value: 'taskdescription', title: 'Discription', task: ['createtask'], required: true },
                { type: 'textarea', value: 'task_due_date', title: 'Due Date', task: ['createtask'], required: true },
                { type: 'textarea', value: 'time_estimate', title: 'Time Estimate (In Minutes)', task: ['createtask'], required: true },
                { type: 'textarea', value: 'start_date', title: 'Start Date', task: ['createtask'], required: true },

            ]
        }
    },
    methods: {

        CheckinDatabase: function(item, name) {

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

        getTeam: function() {
            if (event.target.value.length < 1) {
                this.teamLoading = false;
                return;
            }
            this.teamLoading = true;
            activePlatformId = this.fielddata.activePlatformId;


            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_team',
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.teamList = response.data
                this.teamLoading = false;
            });
        },

        getSpace: function(event) {
            if (event.target.value.length < 1) {
                this.spaceLoading = false;
                return;
            }
            this.spaceLoading = true;
            activePlatformId = this.fielddata.activePlatformId;
            this.fielddata.team_id = event.target.value;

            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_space',
                'team_id' : event.target.value
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.spaceList = response.data
                this.spaceLoading = false;
            });
        },

        getFolder: function(event) {

            if (event.target.value.length < 1) {
                this.folderLoading = false;
                return;
            }
            this.folderLoading = true;
            activePlatformId = this.fielddata.activePlatformId;
            

            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_folder',
                'space_id' : event.target.value
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.folderList = response.data
                this.folderLoading = false;
            });
        },

        getList: function(event) {

            if (event.target.value.length < 1) {
                this.listLoading = false;
                return;
            }
            this.listLoading = true;
            activePlatformId = this.fielddata.activePlatformId;
            

            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_lists',
                'folder_id' : event.target.value
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.listList = response.data
                this.listLoading = false;
            });
        },

        getColour: function(event) {
            if (event.target.value.length < 1) {
                this.colourLoading = false;
                return;
            }

            this.colourLoading = true;
            this.fielddata.teamid = event.target.value;

            this.data.colourList = {
                0: { 'id': "#000000", "name": "Black" },
                1: { 'id': "#FFFFFF", "name": "White" },
                2: { 'id': "#FF0000", "name": "Red" },
                3: { 'id': "#00FF00", "name": "Lime" },
                4: { 'id': "#0000FF", "name": "Blue" },
                5: { 'id': "#FFFF00", "name": "Yellow" },
                6: { 'id': "#00FFFF", "name": "Cyan / Aqua" },
                7: { 'id': "#C0C0C0", "name": "Silver" },
                8: { 'id': "#808080", "name": "Gray" },
                9: { 'id': "#800000", "name": "Maroon" },
                10: { 'id': "#008000", "name": "Green" },
            }
            this.colourLoading = false;
        },

        getPriority: function(event) {
            if (event.target.value.length < 1) {
                this.priorityLoading = false;
                return;
            }

            this.priorityLoading = true;
            this.fielddata.folderid = event.target.value;

            this.data.priorityList = {
                0: { 'id': 1, "name": "Urgent" },
                1: { 'id': 2, "name": "High" },
                2: { 'id': 3, "name": "Low" }
                
            }
            this.priorityLoading = false;
        },

        gettaskPriority: function(event) {
            if (event.target.value.length < 1) {
                this.priorityLoading = false;
                return;
            }

            this.priorityLoading = true;
            this.fielddata.list_id = event.target.value;

            this.data.taskpriorityList = {
                0: { 'id': 1, "name": "Urgent" },
                1: { 'id': 2, "name": "High" },
                2: { 'id': 3, "name": "Normal" },
                3: { 'id': 4, "name": "Low" }
                
            }
            this.priorityLoading = false;
        },

        selectedtaskPriority: function(event) {
            this.fielddata.taskpriorityid = event.target.value;
        },

        selectedPriority: function(event) {
            this.fielddata.priorityid = event.target.value;
        },

        selectedSpace: function(event) {
            this.fielddata.spaceid = event.target.value;
        },

        selectedFolder: function(event) {
            this.fielddata.folderid = event.target.value;
        },

        selectedcolour: function(event) {
            this.fielddata.colour = event.target.value;
        },

        selectedTeam: function(event) {
            this.fielddata.teamid = event.target.value;
        },

        getUserType: function(event) {
            if (event.target.value.length < 1) {
                return;
            }
            this.fielddata.teamid = event.target.value;
            this.data.adminStatusList = {
                0: { 'id': true, 'name': 'Is Admin' },
                1: { 'id': true, 'name': 'Not Admin' }
            }
        },

        selectedUserType: function(event) {
            this.fielddata.adminstatus = event.target.value;
        },

        getTagsEdit: function(event) {
            if (event.target.value.length < 1) {
                return;
            }
            this.fielddata.teamid = event.target.value;
            this.data.tagEditStatusList = {
                0: { 'id': true, 'name': 'True' },
                1: { 'id': false, 'name': 'False' }
            }
        },

        getTimeSpent: function(event) {
            if (event.target.value.length < 1) {
                return;
            }
            this.fielddata.can_edit_tags = event.target.value;
            this.data.timeSpentStatusList = {
                0: { 'id': true, 'name': 'True' },
                1: { 'id': false, 'name': 'False' }
            }
        },

        getTimeEstimate: function(event) {
            if (event.target.value.length < 1) {
                return;
            }
            this.fielddata.can_see_time_spent = event.target.value;
            this.data.timeEstimateStatusList = {
                0: { 'id': true, 'name': 'True' },
                1: { 'id': false, 'name': 'False' }
            }
        },

        getCanCreateViews: function(event) {
            if (event.target.value.length < 1) {
                return;
            }
            this.fielddata.can_see_time_estimated = event.target.value;
            this.data.createViewStatusList = {
                0: { 'id': true, 'name': 'True' },
                1: { 'id': false, 'name': 'False' }
            }
        },

        selecteedCreateViews: function(event) {
            this.fielddata.can_create_views = event.target.value;
        }



    },
    template: '#clickup-action-template'
});