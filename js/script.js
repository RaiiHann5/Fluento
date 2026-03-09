// Daftar bendera (kode negara)
        const flags = [
            'gb',  // Inggris
            'id',  // Indonesia
            'us',  // Amerika
            'de',  // Jerman
            'fr',  // Prancis
            'jp',  // Jepang
            'kr',  // Korea
            'it',  // Italia
            'es',  // Spanyol
            'cn',  // China
        ];
        
        let currentIndex = 0;
        const flagImg = document.getElementById('rotating-flag');
        
        function changeFlag() {
            // Fade out
            flagImg.style.opacity = '0';
            
            setTimeout(() => {
                // Ganti ke bendera berikutnya
                currentIndex = (currentIndex + 1) % flags.length;
                flagImg.src = `https://flagcdn.com/w320/${flags[currentIndex]}.png`;
                
                // Fade in
                flagImg.style.opacity = '1';
            }, 400); // Tunggu fade out selesai
        }
        
        // Ganti bendera setiap 2 detik
        setInterval(changeFlag, 2000);
        
        // Set initial opacity
        flagImg.style.opacity = '1';
        flagImg.style.transition = 'opacity 0.4s ease-in-out';