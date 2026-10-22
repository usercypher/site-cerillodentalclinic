
    <footer class="container columns col-gapless bg-primary text-secondary" style="padding: 2em 1em;">  
        <p class="column col-12 text-center">&copy; <?= date('Y') ?> Cerillo Dental Clinic. All rights reserved.</p>

        <div class="column col-12 divider text-center"></div>

        <p class="column col-6 col-md-12">
            <strong>Disclaimer:</strong> This website is currently under active development. Content, features, and functionality may change without notice. While efforts are made to ensure accuracy and reliability, final information will be confirmed upon full launch.
        </p>

        <p class="column col-6 col-md-12">
            <strong>Privacy Policy:</strong> Any personal data provided through this website is handled with care and used solely for internal purposes. No information is shared or sold to third parties. Users will have control over their data and may request deletion at any time.
        </p>

        <p class="column col-6 col-md-12">
            <strong>Terms of Service:</strong> By accessing this website, you agree to use it responsibly. Features may be subject to change, and access may be temporarily limited during updates. No warranties are provided during the development phase.
        </p>

        <p class="column col-6 col-md-12">
            <strong>Account Deletion:</strong> Users may request deletion of their accounts and related data at any time. Please allow some time for the request to be fully processed across all systems.
        </p>

        <p class="column col-12">
            <strong>Capstone Project Notice:</strong> This website is developed as part of a Capstone Project by BSIT students from <strong>St. John Paul II College of Davao</strong>. If you have any concerns or believe this may be a scam, please contact <a href="mailto:lloydmiles_bersabe@sjp2cd.edu.ph" class="text-secondary">lloydmiles_bersabe@sjp2cd.edu.ph</a> for verification.
        </p>
    </footer>

    <!-- Flash Messages Modal -->
    <div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 699;">
        <div id="flash-messages" style="margin: auto; padding: 0 1em">
        </div>
    </div>

    <script>
        (window.init = window.init || []).push(function () {
            Utils.run(function() {
                return window.ElXInit;
            }, function() {
                if (<?= empty($flash) ? 'false' : 'true' ?>) {
                    flashTpl(<?= json_encode($flash, JSON_UNESCAPED_SLASHES) ?>);
                }
            });
        });

        window.flashId = 0;

        function flashTpl(msgs) {
            var tpl = "";
            for (var i = 0; i < msgs.length; i++) {
                var type = msgs[i].type === "error" ? "toast-error" : "toast-success";
                tpl += `
                    <div class="toast ${type}" style="margin: 1em auto; max-width: 400px; min-width: 200px;" x-ref--flash-message-${window.flashId}>
                        <button class="btn btn-clear float-right" x-ref--flash-message-${window.flashId}-close x-on-click x-set--flash-message-${window.flashId}.style="display: none;"></button>
                        ${msgs[i].message}
                    </div>
                `;
                (function(flashIdCopy) {
                    setTimeout(function() {
                        ElX.run("-flash-message-" + flashIdCopy + "-close", "x-on-click");
                    }, 5000);
                })(window.flashId);

                window.flashId++;
            }
            
            var flash = document.getElementById("flash-messages");
            flash.innerHTML = tpl;
            ElX.init(flash.getElementsByTagName("*"));
        }
    </script>
    <div>
    
    <div style="position: fixed; right: 0; bottom: 0; z-index: 799; ">
        <!-- Chat Window -->
        <div style="padding: 1em;">
            <div class="panel d-hide" style="float: right; background: white; max-width: 90%;" x-ref--chat-window>
                <div class="panel-header columns">
                    <div class="panel-title column col-9">Chat</div>
                    <button id="chat-clear" class="btn btn-sm column col-3"><i class="icon icon-cross"></i> Clear</button>
                </div>
                <div class="panel-body" id="chat-messages" style="height: 270px;">
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input type="text" class="form-input" id="chat-input" placeholder="Type a message..." x-ref--chat-window-input>
                        <button class="btn btn-primary input-group-btn" id="chat-send">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Chat Button -->
        <div style="padding: 1em; float: right;">
            <button style="" class="btn btn-primary btn-action btn-lg s-circle" x-ref--chat-toggle x-on-click x-rot--chat-window="d-hide _" x-focus="-chat-window-input" x-var--chat-toggle="">
                <i class="icon icon-message"></i>
            </button>
        </div>
    </div>

    <script>
        (window.init = window.init || []).push(function () {
            ElX.tap("-chat-toggle", Utils.throttle(function () {
                Utils.script(["<?= $app->urlWeb('asset/js/Chatbot.js') ?>"], function () {
                    new Chatbot().init("<?= $app->urlRoute('chatbot/query') ?>");
                    delete ElX.taps["-chat-toggle"];
                });
            }, 300));
        });
    </script>

    <script>
        (window.init = window.init || []).push(function () {
            ElX.init(document.getElementsByTagName("*"));
            window.ElXInit = true;
        });
    </script>

    <script>
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                form.querySelectorAll('input[type="submit"]').forEach(el => {
                    el.disabled = true;
                    button.classList.add("loading");
                });
                form.querySelectorAll('button').forEach(button => {
                    const type = button.getAttribute('type');
                    if (type === null || type.toLowerCase() === 'submit') {
                        button.disabled = true;
                        button.classList.add("loading");
                    }
                });
            });
        });
    </script>


