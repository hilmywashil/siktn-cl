@extends ('layouts.app')

@section('title', 'How to Join - ASITA JABAR')

@section('content')
    <section class="hero-howtojoins">
        <div class="hero-howtojoin" data-aos="fade-up">
            <h1>How to Join?</h1>
            <p>Procedures for ASITA WEST JAVA Membership</p>
        </div>
    </section>
    <section class="howtojoin-cards-wrapper">
        <div class="howtojoin-cards" data-aos="fade-up">
            <div class="howtojoin-card">
                <div class="howtojoin-card-icons">
                    <div class="step-number">1</div>
                    <img src="{{ asset('images/icons/list.png') }}" alt="">
                </div>
                <div class="howtojoin-card-text">
                    <h3>1. Completing the Requirements</h3>
                    <ol class="alpha-list">
                        <li>Click here to see the terms.</li>
                        <li>Requirements can also be obtained at the office of Asita Secretariat West Java.</li>
                    </ol>
                </div>

            </div>
            <div class="howtojoin-card">
                <div class="howtojoin-card-icons">
                    <div class="step-number">2</div>
                    <img src="{{ asset('images/icons/writing.png') }}" alt="">
                </div>
                <div class="howtojoin-card-text">
                    <h3>2. Fill out the Application Form</h3>
                    <ol class="alpha-list">
                        <li>Click here to get / download the application form. The application form can be filled using a
                            computer.</li>
                        <li>Application forms can also be obtained at the office of Asita Secretariat West Java.</li>
                    </ol>
                </div>

            </div>
            <div class="howtojoin-card">
                <div class="howtojoin-card-icons">
                    <div class="step-number">3</div>
                    <img src="{{ asset('images/icons/apply.png') }}" alt="">

                </div>
                <div class="howtojoin-card-text">
                    <h3>3. Submit the Terms and Application Form</h3>
                    <ol class="alpha-list">
                        <li>If the completed application form has been filled in and the terms have been fulfilled, then you
                            can submit a permit application directly to the office at the Asita Secretariat office in West
                            Java, located at Jl.Tamblong No. 8 Kb. Pisang, Sumur, Kota Bandung – 40112.</li>
                        <li>Membership Arrangement.</li>
                    </ol>
                </div>

            </div>
        </div>
    </section>
    <section class="howtojoin-tables">
        <div class="howtojoin-table">
            <div class="table-wrapper" data-aos="fade-up">
                <table class="process-table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>PHASE</th>
                            <th>EXECUTORS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>Complete the requirements & fill out the membership form.</td>
                            <td>Applicant</td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>File reception & File receipt.</td>
                            <td>Secretariat</td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Inspection File</td>
                            <td>Chairman / Secretary</td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>Notice to members by email in order to request advice.</td>
                            <td>Secretariat (required 1 week)</td>
                        </tr>
                        <tr>
                            <td>5.</td>
                            <td>
                                If the Application is accepted by the member, the Asita West Java Secretariat
                                shall forward to the ASITA DPP for the issuance of the Decree and the Membership
                                Charter, on the contrary if there is a rebuttal it shall be suspended.
                            </td>
                            <td>Secretariat (required 1 month)</td>
                        </tr>
                        <tr>
                            <td>6.</td>
                            <td>
                                When the SK & Charter of Membership has been issued, the Applicant shall
                                immediately take to the Asita Secretariat by completing all administrative obligations.
                            </td>
                            <td>Applicant</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection