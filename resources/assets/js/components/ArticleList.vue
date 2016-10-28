<template>
    <div class="panel panel-default">

        <div class="panel-heading">
            Articles
        </div>

        <div class="panel-body">

            <template v-if="this.$root.loading">
                <loading></loading>
            </template>

            <template v-else>

                <template v-if="ithems.length > 0">

                    <input v-model="search">
                    <hr>
                    <table class="table table-responsive table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th class="num">#</th>
                                <th>Name <button :class="[headers.name, headers.name_icon]" @click="setSortBy('name');"></button></th>
                                <th><span>Updated</span><button :class="[headers.updated_at, headers.updated_at_icon]" @click="setSortBy('updated_at');"></button></th>
                                <th class="text-center">Public</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(ithem, index) in filterIthems">
                                <td class="num"><strong>{{(index+1)+offset}}. </strong></td>
                                <td>
                                    <div class="tooltip-info"
                                        v-tooltip :data-original-title="displaySummary(ithem.desc)">
                                        {{ithem.name}}
                                    </div>
                                </td>

                                <td>
                                    {{ithem.updated_at}}
                                </td>

                                <td class="text-center"><span :class="'fa ' + ((ithem.public==1) ? 'fa-check' : 'fa-remove')"></span></td>

                                <td class="tools">
                                    <div class="tool-group">
                                    <a class="btn-stacked btn btn-default btn-sm fa tooltip-interval"
                                        v-tooltip :data-original-title="displayInterval(ithem.publish_interval)"
                                        v-daterangepicker :id="ithem.id" :data-value="ithem.publish_interval" name="publish_interval"
                                        >
                                        <i class="fa fa-calendar-o btn-stacked-bg"></i>
                                        <i :class="'fa fa-dollar btn-stacked-fg '+((ithem.publish_interval!='') ? 'text-primary':'')"></i>
                                    </a>
                                    <a class="btn-stacked btn btn-default btn-sm fa tooltip-interval"
                                        v-tooltip :data-original-title="displayInterval(ithem.bidding_interval)"
                                        v-daterangepicker :id="ithem.id" :data-value="ithem.bidding_interval" name="bidding_interval"
                                        >
                                        <i class="fa fa-calendar-o btn-stacked-bg"></i>
                                        <i :class="'fa fa-gavel btn-stacked-fg '+((ithem.bidding_interval!='') ? 'text-primary':'')"></i>
                                    </a>
                                    <router-link
                                        :to="'/articles/'+ithem.id"
                                        class="btn btn-default btn-sm fa fa-pencil"
                                        v-tooltip data-original-title="Edit"
                                        >
                                    </router-link>
                                    <a class="btn btn-default btn-sm fa fa-trash btn-hover-danger"
                                        v-tooltip data-original-title="Remove"
                                        @click="remove(ithem)">
                                    </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <pagination
                        :total="ithems.length"
                        :show-pagination="(search=='' && !limitOffBtn)"
                    >
                        <div slot="replacePagination">
                            <button v-if="limitOffBtn" class="btn btn-default searchresults_expander" @click="limitOff = true">
                                Visa alla resultat
                            </button>
                        </div>
                    </pagination>

                </template>

                <template v-else>
                    Empty
                </template>

            </template>

        </div>

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
                    name : "btn btn-sm fa",
                    name_icon : 'fa-sort',
                    updated_at : 'btn btn-sm fa',
                    updated_at_icon : 'fa-sort',
                },
            };
        },

        methods: {
            displayInterval(interval){
                var dates = interval.split('|');

                var result = "<b>Start:</b><br>" + dates[0] + "<br>";

                if (dates.length > 1)
                    result += "<br><b>End:</b><br>" + dates[1];

                return result;
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
                        selectedHeader = ((this.desc == 1) ? "fa-sort-alpha-asc"
                            : "fa-sort-alpha-desc");

                        break;

                    case 'updated_at':
                        this.setOrder('updated_at',1);
                        selectedHeader = ((this.desc == 1) ? "fa-sort-numeric-asc"
                            : "fa-sort-numeric-desc");
                        break;

                    default:
                }

                this.headers[headingTitle+'_icon'] = selectedHeader;

                // Change the other (not sorted by) icons to a generic sort icon
                for (var i = 0;i < this.targets.length ; i++){
                    if (this.targets[i] != headingTitle){
                        this.headers[this.targets[i]+'_icon'] = "fa-sort";
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
            console.log('about do create list')
            bus.$on('publish_interval_changed', (id,new_value) => {
                console.log('on')
                for (var i = 0 ; i<this.ithems.length ; i++ )
                    if (this.ithems[i].id == id) {
                        this.ithems[i].publish_interval = new_value;
                        bus.$emit('article_changed',this.ithems[i]);
                    }
            } );

            bus.$on('bidding_interval_changed', (id,new_value) => {
                console.log('on')
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
            console.log('about do destroy list')
            bus.$off('publish_interval_changed');
            bus.$off('bidding_interval_changed');
            bus.$off('offset_changed');
            bus.$off('limit_changed');
        }

    };
</script>
