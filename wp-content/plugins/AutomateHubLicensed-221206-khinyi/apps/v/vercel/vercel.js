Vue.component('vercel', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            teamLoading: false,
            frameworkLoading: false,
            data: { teamList: [], frameworkList: [] },
            fields: [
                // create team
                { type: 'textarea', value: 'name', title: 'Name', task: ['createteam'], required: true },
                { type: 'textarea', value: 'slug', title: 'Slug', task: ['createteam'], required: true },
                // create project
                { type: 'textarea', value: 'projectname', title: 'Project Name', task: ['createproject'], required: true },
                // create secret
                { type: 'textarea', value: 'secretname', title: 'Secret Name', task: ['createsecret'], required: true },
                { type: 'textarea', value: 'secretvalue', title: 'Secret Value', task: ['createsecret'], required: true },


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

        selectedframework: function(event) {
            this.fielddata.framework = event.target.value;

        },


        getframeworkList: function(event) {
            this.fielddata.teamid = event.target.value;
            this.data.frameworkList = {
                0: { 'id': 'blitzjs', 'name': 'blitzj' },
                1: { 'id': 'nextjs', 'name': 'nextjs' },
                2: { 'id': 'gatsby', 'name': 'gatsby' },
                3: { 'id': 'remix', 'name': 'remix' },
                4: { 'id': 'astro', 'name': 'astro' },
                5: { 'id': 'hexo', 'name': 'hexo' },
                6: { 'id': 'eleventy', 'name': 'eleventy' },
                7: { 'id': 'docusaurus-2', 'name': 'docusaurus-2' },
                8: { 'id': 'docusaurus', 'name': 'docusaurus' },
                9: { 'id': 'preact', 'name': 'preact' },
                10: { 'id': 'solidstart', 'name': 'solidstart' },
                11: { 'id': 'dojo', 'name': 'dojo' },
                12: { 'id': 'ember', 'name': 'ember' },
                13: { 'id': 'vue', 'name': 'vue' },
                14: { 'id': 'scully', 'name': 'scully' },
                15: { 'id': 'ionic-angular', 'name': 'ionic-angular' },
                16: { 'id': 'angular', 'name': 'angular' },
                17: { 'id': 'polymer', 'name': 'polymer' },
                18: { 'id': 'svelte', 'name': 'svelte' },
                19: { 'id': 'sveltekit', 'name': 'sveltekit' },
                20: { 'id': 'ionic-react', 'name': 'ionic-react' },
                21: { 'id': 'create-react-app', 'name': 'create-react-app' },
                22: { 'id': 'gridsome', 'name': 'gridsome' },
                23: { 'id': 'umijs', 'name': 'umijs' },
                24: { 'id': 'sapper', 'name': 'sapper' },
                25: { 'id': 'saber', 'name': 'saber' },
                26: { 'id': 'stencil', 'name': 'stencil' },
                27: { 'id': 'nuxtjs', 'name': 'nuxtjs' },
                28: { 'id': 'redwoodjs', 'name': 'redwoodjs' },
                29: { 'id': 'hugo', 'name': 'hugo' },
                30: { 'id': 'jekyll', 'name': 'jekyll' },
                31: { 'id': 'brunch', 'name': 'brunch' },
                32: { 'id': 'middleman', 'name': 'middleman' },
                33: { 'id': 'zola', 'name': 'zola' },
                34: { 'id': 'vite', 'name': 'vite' },
                35: { 'id': 'parcel', 'name': 'parcel' },
                36: { 'id': 'sanity', 'name': 'sanity' },
                37: { 'id': 'hydrogen', 'name': 'hydrogen' }
            };
        },

        getteam: function() {
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
        }
    },

    template: '#vercel-action-template'
});