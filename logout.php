<?php
session_start();

// 1. Server se session khatam karo
session_unset();
session_destroy();
?>

<script>
    localStorage.removeItem('competition_remaining_time');
    window.location.href = "index.php";
</script>

<?php
exit();
?>