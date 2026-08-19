/**
 * Category Colors - Memberikan warna yang berbeda untuk setiap kategori
 * berdasarkan nama kategori
 */
document.addEventListener('DOMContentLoaded', function() {
    // Warna-warna yang tersedia untuk kategori
    const categoryColors = [
        // Format: [background color, text color]
        ['#dbeafe', '#1e40af'], // Biru
        ['#dcfce7', '#166534'], // Hijau
        ['#f3e8ff', '#6b21a8'], // Ungu
        ['#fef3c7', '#92400e'], // Kuning
        ['#fee2e2', '#b91c1c'], // Merah
        ['#e0e7ff', '#3730a3'], // Indigo
        ['#fce7f3', '#831843'], // Pink
        ['#ede9fe', '#5b21b6'], // Violet
        ['#ffedd5', '#9a3412'], // Oranye
        ['#d1fae5', '#065f46'], // Emerald
        ['#ccfbf1', '#115e59'], // Teal
        ['#cffafe', '#155e75']  // Cyan
    ];

    // Fungsi untuk menghasilkan indeks warna berdasarkan nama kategori
    function getColorIndexForCategory(categoryName) {
        let hash = 0;
        // Buat nilai hash sederhana dari nama kategori
        for (let i = 0; i < categoryName.length; i++) {
            hash = ((hash << 5) - hash) + categoryName.charCodeAt(i);
            hash = hash & hash; // Convert to 32bit integer
        }
        // Gunakan nilai absolut dan modulo untuk mendapatkan indeks warna
        return Math.abs(hash) % categoryColors.length;
    }

    // Temukan semua elemen kategori di halaman
    const categoryElements = document.querySelectorAll('.news-category');
    
    console.log('Found', categoryElements.length, 'category elements');
    
    // Terapkan warna yang sesuai untuk setiap kategori
    categoryElements.forEach(function(element) {
        // Ambil teks kategori dari data-attribute atau text content
        const categoryText = element.getAttribute('data-category') || element.textContent.trim();
        
        console.log('Processing category:', categoryText);
        
        // Jika kategori "Tidak Berkategori", gunakan warna abu-abu
        if (categoryText === 'Tidak Berkategori') {
            element.style.backgroundColor = '#f3f4f6';
            element.style.color = '#374151';
            return;
        }
        
        // Dapatkan indeks warna berdasarkan nama kategori
        const colorIndex = getColorIndexForCategory(categoryText);
        
        // Terapkan warna background dan teks
        element.style.backgroundColor = categoryColors[colorIndex][0];
        element.style.color = categoryColors[colorIndex][1];
        
        // Tambahkan ikon tag untuk kategori
        const icon = document.createElement('span');
        icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>`;
        
        // Masukkan ikon di awal elemen jika belum ada
        if (!element.querySelector('svg')) {
            element.insertBefore(icon, element.firstChild);
        }
    });
});
