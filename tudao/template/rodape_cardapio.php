
</div>
</div>
</div>
</div>
<!-- footer -->
<div id="footer" class="footer">
    <div class="container">
        <!-- <div class="row">
            <div class="col-sm-3">
                <div class="column">
                    <h4>Information</h4>
                    <ul>
                        <li><a href="about-us.html">About Us</a></li>
                        <li><a href="about-us2.html">Wide page</a></li>
                        <li><a href="about-us3.html">Right column</a></li>
                        <li><a href="typography.html">Typography </a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="column">
                    <h4>Customer Service</h4>
                    <ul>
                        <li><a href="contact-us.html">Contact Us</a></li>
                        <li><a href="request-return.html">Returns</a></li>
                        <li><a href="sitemap.html">Site Map</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="column">
                    <h4>Extras</h4>
                    <ul>
                        <li><a href="brands.html">Brands</a></li>
                        <li><a href="vouchers.html">Gift Vouchers</a></li>
                        <li><a href="haffiliates.html">Affiliates</a></li>
                        <li><a href="search.html">Search</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="column">
                    <h4>My Account</h4>
                    <ul>
                        <li><a href="login.html">Login</a></li>
                        <li><a href="create-account.html">Register</a></li>
                        <li><a href="forgot-password.html">Password</a></li>
                        <li><a href="shopping-cart.html">Cart</a></li>
                        <li><a href="checkout.html">Checkout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <hr> -->
        <div class="row">
            <div class="col-sm-9 ">
                <div id="about">
                    <h3><?php echo ctexto($_SESSION['estabelecimento']); ?> online</h3>
                    <p>
                        <span id="result_box" lang="en">
                            <span class="hps">Seja bem-vindo ao cardápio online de melhores sanduiches da cidade. Aqui você sempre pode pedir sanduiches, doces, salgados e bebidas para entrega em domicílio. Tudão, X-frango, Picanha na Chapa e outros. Você ficará satisfeito com o novo cardápio. Entrega será realizada em vários bairros da cidade, havendo cobrança de uma taxa de acordo com o Bairro.</span>
                        </span>
                    </p>
                </div>
            </div>
            <div class="col-sm-3 text-right">
                <div id="powered">
                    <a href="http://conceptlogic.org/" title="Website design, ecommerce development">Desenvolvido por Ubirajara Jucá e Ildeniro Lima</a>
                    <br> 
                    <?php echo ctexto($_SESSION['estabelecimento']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- //footer -->
</div>
</body>
<!-- jQuery 2.1.4 -->
<script src="<?= PLUGINS_FOLDER; ?>jQuery/jQuery-2.1.4.min.js"></script>
<script type="text/javascript" src="<?= PLUGINS_FOLDER; ?>template2/js/jquery.min.js"></script>
<script type="text/javascript" src="<?= PLUGINS_FOLDER; ?>template2/js/jquery.cycle.js"></script>
<script type="text/javascript" src="<?= PLUGINS_FOLDER; ?>template2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= PLUGINS_FOLDER; ?>template2/js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="<?= PLUGINS_FOLDER; ?>template2/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?= PLUGINS_FOLDER; ?>template2/js/jquery.fitvids.js"></script>
<script type="text/javascript" src="<?= PLUGINS_FOLDER; ?>template2/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?= PLUGINS_FOLDER; ?>template2/js/common.js"></script>

<!-- InputMask -->
<script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>

<!-- Sweetalert -->
<script type="text/javascript" src="<?= PLUGINS_FOLDER; ?>sweetalert/dist/sweetalert.min.js"></script>

<!-- Livequery -->
<script src="<?= JS_FOLDER ?>livequery.js" type="text/javascript"></script>

<script type="text/javascript" src="<?= JS_FOLDER ?>utils.js"></script>
<script type="text/javascript" src="<?= JS_FOLDER ?>projeto.utils.js"></script>

<!-- MÁSCARA PARA DINHEIRO -->
<script type="text/javascript" src="<?= ASSETS_FOLDER; ?>js/jquery.price_format.1.3.js"></script>

</html>