<script>
    import swal from 'sweetalert';

    export default {

        methods: {

            showError(what){

                return swal(what.title, what.message, "error");

            }

        }

    };
</script>
