@extends('layoutuser.app')

@section('title', 'Index')

@section('content')
    <section id="home" class="py-5" >
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start text-center">
                    <h1><span class="black-text">Klinik Mabarrot Hasyimiyah</span> <span class="orange-text">Manyar
                            Gresik</span></h1>
                    <p>Pelayanan terbaik untuk kesehatan Anda! Daftarkan diri Anda dengan mudah dan cepat melalui layanan
                        antrian online kami.</p>
                    <a href="pesan" class="btn btn-primary mt-3"
                        style="background-color: #EC744A; color: white; padding: 15px 25px; border-radius: 30px; font-weight: 700; text-decoration: none; display: inline-block; transition: 0.3s ease-in-out; border: none;">
                        Daftar Antrian Online
                    </a>
                </div>
                <div class="col-md-6 text-center">
                    <img src="user/img/cardio.png" alt="Ilustrasi Dokter" class="img-doctor">
                </div>
            </div>
        </div>
    </section>
    <div class="monitor"><a class="link" href="monitor">
            <h1>Monitoring Antrian Online Real Time</h1>
        </a>
    </div>
    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 about">
                    <img src="user/img/about.png" alt="about" class="img-about">
                </div>
                <div class="col-md-6 about-text">
                    <h1><span class="orange-text">About</span> <span class="black-text">Us</span></h1>
                    <br>
                    <p>
                        Klinik Mabarrot Hasyimiyah Manyar, yang berdiri sejak 26 April 1987, merupakan satu-satunya klinik
                        rawat inap di Kabupaten Gresik yang beroperasi 24 jam dan melayani BPJS Kesehatan maupun
                        Ketenagakerjaan selama 24 jam penuh. Klinik ini juga menyediakan layanan perpindahan faskes dan
                        pembuatan BPJS baru, dengan dokter, perawat, dan bidan yang selalu siaga sepanjang waktu untuk
                        memberikan pelayanan terbaik kepada masyarakat. Dengan komitmen tinggi terhadap mutu pelayanan dan
                        kenyamanan pasien, Klinik Mabarrot terus berinovasi dan meningkatkan fasilitas guna memenuhi
                        kebutuhan kesehatan masyarakat secara menyeluruh. Didukung oleh tenaga medis profesional dan
                        berpengalaman, kami hadir sebagai solusi kesehatan yang mudah dijangkau, terpercaya, dan humanis
                        bagi seluruh lapisan masyarakat di Gresik dan sekitarnya.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section id="services" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1><span class="black-text">Our</span> <span class="orange-text">Services</span></h1>
                    <br>
                    <p>Dengan lebih dari 10 Dokter Spesialis dan Sub Spesialis yang kompeten, kami berupaya untuk memberikan
                        pelayanan medis yang tepat untuk setiap kebutuhan kesehatan anda.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="service-list">
                                <li>▶ UGD 24 JAM</li>
                                <li>▶ PERSALINAN 24 JAM</li>
                                <li>▶ RAWAT INAP 24 JAM</li>
                                <li>▶ POLI UMUM</li>
                                <li>▶ POLI GIGI</li>
                                <li>▶ POLI KIA/KB</li>
                                <li>▶ POLI KANDUNGAN</li>
                                <li>▶ POLI VAKSINASI</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="service-list">
                                <li>▶ LABORATORIUM</li>
                                <li>▶ AMBULANCE 24 JAM</li>
                                <li>▶ APOTEK 24 JAM</li>
                                <li>▶ KOPERASI</li>
                                <li>▶ HOME CARE</li>
                                <li>▶ OPERASI KECIL</li>
                                <li>▶ KHITAN</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <img src="user/img/service.png" alt="Services" class="img-services">
                </div>
            </div>
        </div>
    </section>
    <section id="contact" class="contact-section">
        <div class="row align-items-center">
            <div class="col-md-4 text-center d-flex align-items-center justify-content-center">
                <img src="user/img/logo.png" alt="Logo Klinik" class="logo" style="margin-left: 10px">
                <h2 style="font-size: 20px">Klinik Mabarrot Hasyimiyah Manyar Gresik</h2>
            </div>
            <div class="col-md-4 text-left contact-info">
                <h3>Contact Us :</h3>
                <p><i class="bi bi-telephone"></i> 031-399-28461 (082333669990)</p>
                <p><i class="bi bi-facebook"></i> <a href="https://www.facebook.com/klinik.hasyimiyahmanyar.3/"
                        class="text-white">klinik.hasyimiyahmanyar</a></p>
                <p><i class="bi bi-instagram"></i> <a href="https://www.instagram.com/klinikhasyimiyahmanyar/"
                        class="text-white">klinikhasyimiyahmanyar</a></p>
                <p><i class="bi bi-tiktok"></i> <a
                        href="https://www.tiktok.com/@klinikhasyimiyahmanyar?is_from_webapp=1&sender_device=pc"
                        class="text-white">klinikhasyimiyahmanyar</a></p>
                <p><i class="bi bi-geo-alt"></i> Jl. Kyai Sahlan I No.21, Manyarejo, Kec. Manyar, Kabupaten Gresik, Jawa
                    Timur 61151, Indonesia</p>
            </div>
            <div class="col-md-4 text-center">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.045881142808!2d112.59997597524612!3d-7.120681492883062!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e77fe0e86cb6845%3A0x22f14154496ae95d!2sKlinik%20Mabarrot%20Hasyimiyah%20NU%20Manyar!5e0!3m2!1sen!2sid!4v1739096857037!5m2!1sen!2sid"
                    width="300" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <hr class="mt-2">
            <p class="text-center mt-2">&copy; 2025 @klinikmabarrothasyimiyahmanyar all rights reserved</p>
        </div>
    </section>
@endsection
