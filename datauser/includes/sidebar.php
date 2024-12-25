<style>
    aside {
        z-index: 1020; 
        height: 100%;
        box-shadow: 2px 0 6px rgba(0, 0, 0, 0.1);
        padding-top: 15px
    }
    aside ul li {
        margin-bottom: 20px;
    }
    .nav-link {
        color:#4a4a4a;
        font-weight: 530;
    }
    .nav-link.active {
        background-color:#5f5f5f;
        color:#4a4a4a !important;
        border-radius: 5px;
    }

    .nav-link:hover {
        color:#505050 !important;
        background-color: rgba(123, 123, 123, 0.1);
        border-radius: 5px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
</style>

<aside class="bg-light border-end vh-100" style="width: 25%; position: fixed;">
    <ul class="nav flex-column p-3">
        <li class="nav-item">
            <a href="../dashboard" class="nav-link">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-people"></i> Data User
            </a>
        </li>
        <li class="nav-item">
            <a href="../datakaryawan" class="nav-link">
                <i class="bi bi-person-workspace"></i> Data Karyawan
            </a>
        </li>
        <li class="nav-item">
            <a href="../datadivisi" class="nav-link">
                <i class="bi bi-collection"></i> Data Divisi
            </a>
        </li>
        <li class="nav-item">
            <a href="../datagolongan" class="nav-link">
                <i class="bi bi-tags"></i> Data Golongan
            </a>
        </li>
        <li class="nav-item">
            <a href="../datagaji" class="nav-link">
                <i class="bi bi-cash-stack"></i> Data Gaji
            </a>
        </li>
        <li class="nav-item">
            <a href="../datapenggajian" class="nav-link">
                <i class="bi bi-cash-coin"></i> Data Penggajian
            </a>
        </li>
        <li class="nav-item">
            <a href="../service/logout.php" class="nav-link text-danger">
                <i class="bi bi-box-arrow-right"></i> LogOut
            </a>
        </li>
    </ul>
</aside>