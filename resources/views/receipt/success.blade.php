<div>
    <a id="openAppLink" href="myapp://success/openapp?payment_id={{ $reference }}">Open Android App</a>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Automatically click the link to open the Android app
        const link = document.getElementById('openAppLink');
        window.location.href = link.href;
    });
</script>
