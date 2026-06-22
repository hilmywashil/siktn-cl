<footer class="site-footer" id="contact">
    <div class="footer-grid">
        <!-- Logo & Description -->
        <div class="footer-brand">
            <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" alt="Logo Karang Taruna" class="footer-logo">
            <p class="footer-desc">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </p>
        </div>

        <!-- Navigasi -->
        <div class="footer-col">
            <h3 class="footer-heading">Navigasi</h3>
            <ul class="footer-links">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="#">Tentang Kami</a></li>
                <li><a href="#">Program & Layanan</a></li>
                <li><a href="#">Kegiatan</a></li>
                <li><a href="{{ route('e-katalog') }}">E-Catalog</a></li>
                <li><a href="{{ route('berita') }}">Berita/Blog</a></li>
                <li><a href="#">Kontak</a></li>
            </ul>
        </div>

        <!-- Program Unggulan -->
        <div class="footer-col">
            <h3 class="footer-heading">Layanan Kami</h3>
            <ul class="footer-links">
                <li><a href="#">Program CSR</a></li>
                <li><a href="#">Program Bidang</a></li>
                <li><a href="#">Struktur Organisasi</a></li>
                <li><a href="#">Daftar Anggota</a></li>
                <li><a href="#">Login Member</a></li>
            </ul>
        </div>

        <!-- Kontak Kami -->
        <div class="footer-col footer-contact">
            <h3 class="footer-heading">Kontak Kami</h3>
            <div class="footer-contact-list">
                <div>
                    <h4 class="footer-contact-label">Alamat</h4>
                    <p class="footer-contact-text">
                        Jl Terusan Mars Utara III<br>No. 8D Kota Bandung, 40292
                    </p>
                </div>
                <div>
                    <h4 class="footer-contact-label">Email</h4>
                    <p class="footer-contact-text">karangtaruna@mail.com</p>
                </div>
                <div>
                    <h4 class="footer-contact-label">Telepon</h4>
                    <p class="footer-contact-text">+62 812-XXXX-XXXX</p>
                </div>
                <div>
                    <h4 class="footer-contact-label">Media Sosial</h4>
                    <div class="footer-socials">
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bottom Bar -->
<div class="footer-bottom">
    <div class="footer-bottom-inner">
        <p class="footer-copyright">
            Copyright &copy; 2026 Karang Taruna | Powered by
            <a href="https://www.cyberlabs.co.id" target="_blank">CyberLabs</a>
        </p>
        <div class="footer-bottom-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms &amp; Conditions</a>
        </div>
    </div>
</div>