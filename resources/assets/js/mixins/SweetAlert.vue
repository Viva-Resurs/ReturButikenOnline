<script>
    import swal from 'sweetalert';

    export default {

        methods: {

            showError(what){
                var title = what.title || 'Error';
                var message = what.message || ' ';

                return swal(title, message, "error");
            }

        }

    };
</script>
