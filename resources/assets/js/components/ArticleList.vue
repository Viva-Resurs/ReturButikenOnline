<template>
    <div class="ui container segment">

        <div class="ui dividing header">
            Arkiv
        </div>

            <template v-if="this.$root.loading">
                <loading></loading>
            </template>

            <template v-else>

                <template v-if="ithems.length > 0">
                    <div class="ui icon input">
                        <input v-model="search" class="prompt" placeholder="Type to search">
                        <i class="search icon"></i>
                    </div>

                    <table class="ui compact celled table">
                        <thead>
                            <tr>
                                <th class="num">#</th>
                                <th @click="setSortBy('name');" class="link">
                                    Name
                                    <i :class="[headers.name, headers.name_icon]" ></i>
                                </th>
                                <th @click="setSortBy('updated_at');" class="link">
                                    Updated
                                    <i :class="[headers.updated_at, headers.updated_at_icon]"></i>
                                </th>

                                <th class="center aligned">Public</th>
                                <th class="center aligned">Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(ithem, index) in filterIthems">
                                <td class="num"><strong>{{(index+1)+offset}}. </strong></td>
                                <td v-tooltip :data-html="displaySummary(ithem.desc)">
                                    {{ithem.name}}
                                </td>

                                <td>
                                    {{ithem.updated_at}}
                                </td>

                                <td class="center aligned">
                                    <i :class="'ui icon ' + ((ithem.public==1) ? 'checkmark box' : 'square outline')"
                                        v-tooltip :data-html="((ithem.public==1) ? 'Publicerad för allmänheten' : 'Publicerad på kommunens intranät')"
                                    ></i>
                                </td>

                                <td class="collapsing">
                                    <div class="ui icon basic buttons">
                                        <button class="ui icon button hover-default"
                                            v-tooltip :data-html="displayInterval(ithem.publish_interval)"
                                            v-daterangepicker :id="ithem.id" :data-value="ithem.publish_interval" name="publish_interval"
                                            >
                                            <i :class="'ui icon dollar '+((activeInterval(ithem.publish_interval)) ? 'active-interval':'')"></i>
                                        </button>
                                        <button class="ui icon button hover-default"
                                            v-tooltip :data-html="displayInterval(ithem.bidding_interval)"
                                            v-daterangepicker :id="ithem.id" :data-value="ithem.bidding_interval" name="bidding_interval"
                                            >
                                            <i :class="'ui icon legal '+((activeInterval(ithem.bidding_interval)) ? 'active-interval':'')"></i>
                                        </button>
                                        <router-link
                                            :to="'/articles/'+ithem.id"
                                            class="ui icon button hover-primary"
                                            v-tooltip data-html="Edit"
                                            >
                                            <i class="ui icon pencil"></i>
                                        </router-link>

                                        <button class="ui icon button hover-danger"
                                            v-tooltip data-html="Remove"
                                            @click="remove(ithem)">
                                            <i class="ui icon trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5">
                                    <pagination
                                        :total="ithems.length"
                                        :show-pagination="(search=='' && !limitOffBtn)"
                                    >
                                        <div slot="replacePagination">
                                            <button v-if="limitOffBtn" class="ui button searchresults_expander" @click="limitOff = true">
                                                Visa alla resultat
                                            </button>
                                        </div>
                                    </pagination>
                                </th>
                            </tr>
                      </tfoot>
                    </table>

                </template>

                <template v-else>
                    Empty
                </template>

            </template>



    </div>
</template>

<script>
    import Filters from '../mixins/Filters.vue';
    import Pagination from './Pagination.vue';

    export default {

        name: 'ArticleList',

        mixins: [ Filters ],

        components: { Pagination },

        props: [ 'ithems' ],

        computed: {
            filterIthems(){
                return this.ithems
                    .filter(
                        (ithem) => (ithem.removed!==true)
                    )
                    .filter(
                        (ithem) => this.filterBy(ithem,this.search,this.targets)
                    )
                    .sort(
                        (a,b) => this.shallowSort(a[this.order],b[this.order],this)
                    )
                    .filter(
                        (ithem,index) => this.rangeFilter(ithem,index,this)
                    );
            }
        },

        data: function(){
            return {
                search: '',
                targets: ['name','updated_at'],

                order: 'name',
                desc: -1,

                limitOff: false,
                limitOffBtn: false,

                offset: 0,
                maxIthems: 10,
                headers : {
                    name : "ui icon",
                    name_icon : 'sort',
                    updated_at : 'ui icon',
                    updated_at_icon : 'sort',
                },
            };
        },

        methods: {
            displayInterval(interval){
                if (interval.indexOf('|') < 0)
                    return 'Click to set interval';

                var dates = interval.split('|');

                if (dates.length < 2)
                    return false;

                return "<b>Start:</b><br>" + dates[0] + "<br><br>"+
                       "<b>End:</b><br>" + dates[1];
            },
            activeInterval(interval){
                if (interval=='' || typeof interval !='string' || interval.split('|')[1]=='')
                    return false;
                return moment().isBefore( interval.split('|')[1] );
            },
            displaySummary(info){
                var formated = info.replace(/\n/g,'<br>');
                return formated;
            },
            remove(ithem){
                // Validation?
                bus.$emit('article_remove',ithem);
            },
            openInterval(e){
                $(e.target).closest('td').find('input').trigger('click');
                console.log( $(e.target).closest('td').find('input') );
            },

            setSortBy(headingTitle){

                // Set correct sort icon to the header (ascending, descending)
                var selectedHeader = '';

                switch (headingTitle) {
                    case 'name':
                        this.setOrder('name');
                        selectedHeader = ((this.desc == 1) ? "sort alphabet ascending icon"
                            : "sort alphabet descending icon");

                        break;

                    case 'updated_at':
                        this.setOrder('updated_at',1);
                        selectedHeader = ((this.desc == 1) ? "sort numeric ascending icon"
                            : "sort numeric descending icon");
                        break;

                    default:
                }

                this.headers[headingTitle+'_icon'] = selectedHeader;

                // Change the other (not sorted by) icons to a generic sort icon
                for (var i = 0;i < this.targets.length ; i++){
                    if (this.targets[i] != headingTitle){
                        this.headers[this.targets[i]+'_icon'] = "sort";
                    }
                }

            },
        },

        watch: {
            // Reset show all results when editing search
            search: function(val, oldVal){
                this.offset = 0;
                this.limitOff = false;
            },
            maxIthems: function(val, oldVal){
                this.offset = 0;
            }
        },

        created: function(){
            bus.$on('publish_interval_changed', (id,new_value) => {
                for (var i = 0 ; i<this.ithems.length ; i++ )
                    if (this.ithems[i].id == id) {
                        this.ithems[i].publish_interval = new_value;
                        bus.$emit('article_changed',this.ithems[i]);
                    }
            } );

            bus.$on('bidding_interval_changed', (id,new_value) => {
                for (var i = 0 ; i<this.ithems.length ; i++ )
                    if (this.ithems[i].id == id) {
                        this.ithems[i].bidding_interval = new_value;
                        bus.$emit('article_changed',this.ithems[i]);
                    }
            } );

            // Listen for changes in data by components
            bus.$on('offset_changed', new_offset => this.offset = new_offset);
            bus.$on('limit_changed', new_limit => this.maxIthems = new_limit);

        },

        beforeDestroy: function() {
            bus.$off('publish_interval_changed');
            bus.$off('bidding_interval_changed');
            bus.$off('offset_changed');
            bus.$off('limit_changed');
        }

    };
</script>
