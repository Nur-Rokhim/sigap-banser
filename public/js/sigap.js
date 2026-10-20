/**
 * SIGAP BANSER - Frontend Script
 * Auto-dismiss notifications, roman numerals, and dynamic form toggles
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Auto-dismiss flash notifications after 5s
    const flashContainer = document.getElementById('flashMessages');
    if (flashContainer) {
        const alerts = flashContainer.querySelectorAll('.alert');
        alerts.forEach(function (alert) {
            setTimeout(function () {
                alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-6px)';
                setTimeout(function () {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    }

    // 2. Surat Keluar Dynamic Toggle and Live Number Preview
    const formSuratKeluar = document.getElementById('formSuratKeluar');
    if (formSuratKeluar) {
        const jenisRadios = formSuratKeluar.querySelectorAll('input[name="jenis_surat"]');
        const tanggalInput = document.getElementById('tanggal_surat');
        const contohNomor = document.getElementById('contohNomorDisplay');

        const sectionSU = document.getElementById('section_SU');
        const sectionSPH = document.getElementById('section_SPH');
        const sectionST = document.getElementById('section_ST');

        const romanMonths = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];

        function getSelectedJenis() {
            for (let radio of jenisRadios) {
                if (radio.checked) return radio.value;
            }
            return 'SU';
        }

        function updateContohNomor() {
            if (!contohNomor) return;
            const jenis = getSelectedJenis();
            let dateVal = tanggalInput ? tanggalInput.value : null;
            let d = dateVal ? new Date(dateVal) : new Date();

            let monthIndex = isNaN(d.getMonth()) ? (new Date()).getMonth() : d.getMonth();
            let year = isNaN(d.getFullYear()) ? (new Date()).getFullYear() : d.getFullYear();
            let roman = romanMonths[monthIndex] || "X";

            contohNomor.textContent = `000/SKC-${jenis}/${roman}/${year}`;
        }

        function toggleDynamicSections() {
            const jenis = getSelectedJenis();

            if (sectionSU) sectionSU.style.display = (jenis === 'SU') ? 'block' : 'none';
            if (sectionSPH) sectionSPH.style.display = (jenis === 'SPH') ? 'block' : 'none';
            if (sectionST) sectionST.style.display = (jenis === 'ST') ? 'block' : 'none';

            updateContohNomor();
        }

        jenisRadios.forEach(radio => {
            radio.addEventListener('change', toggleDynamicSections);
        });

        if (tanggalInput) {
            tanggalInput.addEventListener('change', updateContohNomor);
        }

        toggleDynamicSections();
    }
});
