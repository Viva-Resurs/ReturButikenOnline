<script>
    import swal from 'sweetalert'

    export default {

        methods: {

            showError(what){

                return swal("Error", what.text, "error");

            }

        }

    };
</script>