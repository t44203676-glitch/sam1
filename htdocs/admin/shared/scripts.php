<script>
    // Injected PHP variables for the external script
    window.currentUserType = "<?php echo $_SESSION['user_type'] ?? 'Guest'; ?>";
    window.requestsByStatus = <?php echo json_encode($requests_by_status ?? []); ?>;
    window.stats = <?php echo json_encode($stats ?? []); ?>;
    window.weeklyStats = <?php echo json_encode($weekly_stats ?? ['labels' => [], 'data' => []]); ?>;
</script>

<!-- Externalized Admin Dashboard Logic for Performance (Caching & Minification) -->
<script src="public/js/admin-logic.min.js"></script>
  
    });
</script>