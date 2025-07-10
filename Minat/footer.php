    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 DSS-TI | Sistem Pendukung Keputusan untuk Pemilihan Peminatan</p>
    </footer>

    <!-- Memuat Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Tombol untuk mengubah tema (dark/light mode)
        document.getElementById('theme-toggle').addEventListener('click', function () {
            document.body.classList.toggle('bg-dark');
            document.body.classList.toggle('text-light');
            document.body.classList.toggle('dark-mode'); // Menambahkan kelas dark-mode
            this.innerHTML = document.body.classList.contains('bg-dark') ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        });
    </script>
</body>
</html>
