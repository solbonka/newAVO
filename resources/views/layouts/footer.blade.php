<footer class="bg-white text-dark py-4" style="font-family: 'Inter',sans-serif;">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <ul class="list-unstyled">
                    <li class="mb-2"><strong>ГОРОДА</strong></li>
                    @foreach(config('footer.arrivals') as $arrival)
                        <li><a href="#" class="footer-list scrollToTop">{{ $arrival }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-5">
                <ul class="list-unstyled">
                    <li class="mb-2"><strong>АВТОВОКЗАЛЫ</strong></li>
                    @foreach(config('footer.автовокзалы.станции') as $station)
                        <li><a href="#avtovokzal-container" class="footer-list">{{ $station['Название'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-3">
                <ul class="list-unstyled">
                    <li class="mb-2"><strong>НАПРАВЛЕНИЯ</strong></li>
                    @foreach(config('popular_routes') as $route)
                        <li><a href="#" class="footer-list scrollToTop">{{ $route['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-2">
                <ul class="list-unstyled">
                    <li class="mb-2"><strong>МЫ В СОЦИАЛЬНЫХ СЕТЯХ</strong></li>
                    <li><a href="https://vk.com/biletavto" target="_blank"><img class="social-media-img" src="/img/vk-logo.svg" alt="vk-logo"></a></li>
                    <li><a href="https://ok.ru/biletavto" target="_blank"><img class="social-media-img" src="/img/ok-logo.svg" alt="ok-logo"></a></li>
{{--                    <li><a href="https://ok.ru/biletavto" target="_blank"><img class="social-media-img" src="/img/viber-logo.svg" alt="viber-logo"></a></li>--}}
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>&copy; {{ date('Y') }} Система бронирования автобусных билетов. Все права защищены.</p>
            </div>
            <div class="col-md-6 text-md-right">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><a href="#" class="footer-list" data-toggle="modal" data-target="#contactModal">Контакты</a></li>
                    <li class="list-inline-item"><a href="#" class="footer-list" data-toggle="modal" data-target="#faqModal">FAQ</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Модальное окно "Контакты" -->
    <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">Контакты</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="anchor-contact">
                        <h3>Контактная информация</h3>
                        <div>
                            <p><b>ООО «БИЛЕТ АВТО»</b></p>
                        </div>
                        <div>
                            <p><span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span> Телефон: <a href="tel:89021668003">8 (9021) 66-80-03</a> (круглосуточно)</p>
                            <p><span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span> Телефон: <a href="tel:83012268003">8 (3012) 26-80-03</a> (с 04:00 до 13:00 ч. в будни по Московскому времени)</p>
                            <p><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Электронная почта: <a href="mailto:info@biletavto.ru">info@biletavto.ru</a></p>
                        </div>
                        <div>
                            <p><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> ОГРН: 1180327010951 ООО «БИЛЕТ АВТО»</p>
                            <p><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> ИНН/КПП 0326562943 / 032601001</p>
                        </div>
                        <div>
                            <p><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> Адрес для почтовых отправлений: 670002, г. Улан-Удэ, ул. Буйко, 20а, офис 3</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно "FAQ" -->
    <div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="faqModalLabel">Часто задаваемые вопросы (FAQ)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="faqAccordion">
                        <!-- Вопрос 1 -->
                        <div class="card">
                            <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <h5 class="mb-0">
                                        Как оформить заказ?
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Для оформления заказа выберите нужный продукт и нажмите кнопку "Купить". Затем следуйте инструкциям на экране.
                                </div>
                            </div>
                        </div>

                        <!-- Вопрос 2 -->
                        <div class="card">
                            <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <h5 class="mb-0">
                                        Какие способы оплаты вы принимаете?
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Мы принимаем различные способы оплаты, включая кредитные карты, PayPal и банковские переводы.
                                </div>
                            </div>
                        </div>

                        <!-- Вопрос 3 -->
                        <div class="card">
                            <div class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <h5 class="mb-0">
                                        Как могу вернуть товар?
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Чтобы вернуть товар, пожалуйста, свяжитесь с нашей службой поддержки. Мы предоставим вам инструкции по возврату.
                                </div>
                            </div>
                        </div>
                        <!-- Добавьте более вопросов аналогично -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</footer>
