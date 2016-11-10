<script>
/*
 * @author ToxicTree
 */
    export default {

        methods: {

            // Pagination
            rangeFilter(ob,index,scope){

                scope.limitOffBtn = false;

                // Show results in range
                if (scope.search!==''){
                    if (!scope.limitOff){

                        if (index<scope.maxIthems)
                            return true;

                        scope.limitOffBtn=true;
                        return false;
                    }
                    else
                        return true;
                }

                // Show contents in range
                return (index >= scope.offset && index < scope.offset+scope.maxIthems);

            },

            // Case insensitive filter
            filterBy(ithem,search,targets){

                if (search === '')
                    return true;

                var s = new RegExp(search,"gi");

                for (var i=0 ; targets && i<targets.length ; i++)
                    if ( s.test(ithem[targets[i]]) )
                        return true;

                return false;
            },

            // Sort objects case insensitive
            shallowSort(a, b, scope) {

                var checkA = a;
                var checkB = b;

                // Check Arguments
                if (checkA===undefined || checkB===undefined)
                    return 0;

                // Not the same type?
                if (typeof checkA != typeof checkB)
                    return 0;

                // Compare numbers
                if (typeof checkA == 'number'){
                    if (checkA == checkB)
                        return 0;
                    return (checkA - checkB) * scope.desc;
                }

                // Compare strings
                if (typeof checkA == 'string'){
                    checkA = checkA.toLowerCase();
                    checkB = checkB.toLowerCase();

                    // Char by char
                    for (var i=0 ; i<checkA.length ; i++){
                        if (checkA[i] < checkB[i])
                            return -1 * scope.desc;
                        if (checkA[i] > checkB[i])
                            return 1 * scope.desc;
                    }
                    return 0;
                }

            },

            // Sort objects by targeting a deeper property
            deepSort(a, b) {

                var checkA = a;
                var checkB = b;

                // Check Arguments
                if (checkA===undefined || checkB===undefined)
                    return 0;

                // Not the same type?
                if (typeof checkA != typeof checkB)
                    return 0;

                // Dig down to the properties to compare
                var level = this.order.split('.');

                for (var i=0; i<level.length ; i++){
                    if (checkA[level[i]]===undefined || checkB[level[i]]===undefined)
                        return 0;
                    checkA = checkA[level[i]];
                    checkB = checkB[level[i]];
                }

                // Compare numbers
                if (typeof checkA == 'number'){
                    if (checkA == checkB)
                        return 0;
                    return (checkA - checkB) * this.desc;
                }

                // Compare strings
                if (typeof checkA == 'string'){
                    checkA = checkA.toLowerCase();
                    checkB = checkB.toLowerCase();

                    // Char by char
                    for (var j=0 ; j<checkA.length ; j++){
                        if (checkA[j] < checkB[j])
                            return -1 * this.desc;
                        if (checkA[j] > checkB[j])
                            return 1 * this.desc;
                    }
                    return 0;
                }

                // Compare arrays
                if (typeof checkA == 'object'){

                    // Compare lengths
                    if (checkA.length != checkB.length)
                        return (checkA.length - checkB.length) * this.desc;

                    // Compare contents (.name)
                    for (var k=0 ; k<checkA.length ; k++){
                        // Nothing to compare
                        if (!checkA[k].name && !checkB[k].name)
                            return 0;

                        // Equal
                        if (checkA[k].name == checkB[k].name)
                            return 0;

                        // Char by char
                        for (var c=0 ; c<checkA[k].name.length ; c++){
                            if (checkA[k].name[c] < checkB[k].name[c])
                                return -1 * this.desc;
                            if (checkA[k].name[c] > checkB[k].name[c])
                                return 1 * this.desc;
                        }

                        return 0;

                    }

                }

            },

            // Set order & toggle desc
            setOrder(what,desc){
                this.desc = (this.order == what) ? this.desc*=-1 : (desc) ? -1 : 1;
                this.order = what || 'updated_at';
            },

        }

    };
</script>
