<footer class="text-center text-lg-start mt-5">
    <div class="container p-4">
        <p class="text-center" style="color: white;">&copy; 2024 Job Portal. All rights reserved.</p>
    </div>
</footer>

</body>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote();
    });

    const toastTrigger = document.getElementById('liveToastBtn')
    const toastLiveExample = document.getElementById('liveToast')

    if (toastTrigger) {
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
        toastTrigger.addEventListener('click', () => {
            toastBootstrap.show()
        })
    }
</script>
<script>
    function createCat() {
        let cat = document.getElementById('category').value;
        if (cat == 1) {
            window.location.href = "../jobs/category.php";
        }
    }
</script>

</html>