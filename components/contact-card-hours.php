<div class="contact-card">
    <div class="contact-icon time">
        <i class="fas fa-clock"></i>
    </div>

    <div class="contact-info">
        <h3>Jam Operasional</h3>
        <p><strong>Senin – Sabtu:</strong> <?= $data['jam_senin'] ?? '08.00 - 20.00 WITA' ?></p>
        <p><strong>Minggu:</strong> <?= $data['jam_minggu'] ?? '09.00 - 18.00 WITA' ?></p>
        <p class="small">Penjemputan terakhir 2 jam sebelum tutup</p>
    </div>
</div>
