<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet">
<script src="https://unpkg.com/grapesjs"></script>
<script src="https://unpkg.com/grapesjs-preset-newsletter"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>
<script src="https://unpkg.com/grapesjs-plugin-ckeditor@0.0.10"></script>

<div id="wrapper">
    <div class="content">
        <div class="row">

            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4><?php echo _l('settings_send_test_email_heading'); ?></h4>
                        <p class="text-muted"><?php echo _l('emailcanvas_settings_send_test_email_string'); ?></p>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="email" class="form-control" name="test_email" data-ays-ignore="true"
                                       placeholder="<?php echo _l('emailcanvas_settings_send_test_email_string'); ?>">
                                <div class="input-group-btn">
                                    <button type="button" onclick="testEmailTemplate()" class="btn btn-info">Test</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>

            <div class="col-md-6">
                <div class="alert alert-info">
                    <?php echo _l('emailcanvas_notify_about_merge_field'); ?>
                </div>

                <button type="button" onclick="importPreset('1')"
                        class="btn btn-success">Preset 1</button>
                <button type="button" onclick="importPreset('2')"
                        class="btn btn-success">Preset 2</button>
            </div>

            <div class="col-md-12">
                <div class="panel_s">
                    <div id="grapesjs-editor"></div>
                </div>
            </div>

            <div class="btn-bottom-toolbar text-right">
                <button type="button" onclick="resetHtmlContent()"
                        class="btn btn-dark"><?php echo _l('emailcanvas_reset_template'); ?></button>
                <button type="button" onclick="saveHtmlContent()"
                        class="btn btn-success"><?php echo _l('emailcanvas_save_template'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    `use strict`;

    $(function () {

        $.Shortcuts.stop();

        editor = grapesjs.init({
            selectorManager: {componentFirst: true},
            clearOnRender: true,
            storageManager: false,
            container: '#grapesjs-editor',
            fromElement: true,
            assetManager: {
                storageType     : '',
                storeOnChange  : true,
                storeAfterUpload  : true,
                upload: 'images',
                assets       : [ ],
                uploadFile: function(e) {
                    var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
                    var formData = new FormData();
                    for(var i in files){
                        formData.append('file-'+i, files[i])
                    }
                    if (typeof csrfData !== "undefined") {
                        formData.append(csrfData["token_name"], csrfData["hash"]);
                    }
                    $.ajax({
                        url: '<?php echo admin_url("emailcanvas/upload_template_editor_images"); ?>',
                        type: 'POST',
                        data: formData,
                        contentType:false,
                        crossDomain: true,
                        dataType: 'json',
                        mimeType: "multipart/form-data",
                        processData:false,
                        success: function(result){
                            var myJSON = [];
                            $.each( result['data'], function( key, value ) {
                                myJSON[key] = value;
                            });
                            var images = myJSON;
                            editor.AssetManager.add(images);
                        }
                    });
                },
            },
            plugins: ['grapesjs-preset-newsletter', 'gjs-plugin-ckeditor'],
            pluginsOpts: {
                'grapesjs-preset-newsletter': {
                    modalLabelImport: 'Paste all your code here below and click import',
                    modalLabelExport: 'Copy the code and use it wherever you want',
                    codeViewerTheme: 'material',
                    importPlaceholder: '<table class="table"><tr><td class="cell">Hello world!</td></tr></table>',
                    cellStyle: {
                        'font-size': '12px',
                        'font-weight': 300,
                        'vertical-align': 'top',
                        color: 'rgb(111, 119, 125)',
                        margin: 0,
                        padding: 0,
                    }
                },
            }
        });

        editor.DomComponents.addType('var-placeholder', {
            model: {
                defaults: {
                    textable: true,
                    placeholder: '{email_template_content}',
                },
                toHTML() {
                    return `${this.get('placeholder')}`;
                },
            },
            view: {
                tagName: 'span',
                events: {
                    'change': 'updatePlh',
                },
                updatePlh(ev) {
                    this.model.set({placeholder: ev.target.value});
                    this.updateProps();
                },
                updateProps() {
                    const {el, model} = this;
                    el.setAttribute('data-gjs-placeholder', model.get('placeholder'));
                },
                onRender() {
                    const {model, el} = this;
                    const currentPlh = model.get('placeholder');
                    const select = document.createElement('select');
                    const options = ['{email_template_content}', '{logo_url}', '{logo_image_with_url}', '{dark_logo_image_with_url}', '{crm_url}', '{admin_url}', '{main_domain}', '{companyname}', '{email_signature}', '{terms_and_conditions_url}', '{privacy_policy_url}'];
                    select.innerHTML = options.map(item => `<option value="${item}" ${item === currentPlh ? 'selected' : ''}>
			${item}
		  </option>`).join('');
                    while (el.firstChild) el.removeChild(el.firstChild);
                    el.appendChild(select);
                    // select.setAttribute('style', 'padding: 5px; border-radius: 3px; border: none; -webkit-appearance: none;');
                    this.updateProps();
                },
            }
        });

        editor.BlockManager.add('simple-block', {
            label: 'Merge Fields',
            content: {type: 'var-placeholder'},
        });

        let builderAssets = JSON.parse(`<?php echo $available_assets; ?>`);
        builderAssets.forEach(function(asset) {
            editor.AssetManager.add(asset);
        });

        var assets = editor.AssetManager.getAll()
        assets.on('remove', function(asset) {

            let imageUrl = asset.get('src');

            $.ajax({
                url: '<?php echo admin_url("emailcanvas/remove_template_editor_image"); ?>',
                method: 'POST',
                data: {image_url: imageUrl},
                success: function (response) {},
                error: function (xhr, status, error) {
                    alert('Failed');
                    console.error(error);
                }
            });
        })

        editor.on('load', (some, argument) => {

            let templateData = JSON.parse(`<?php echo $template_data->template_content; ?>`);

            editor.setComponents(templateData.components);
            editor.setStyle(templateData.style);

        })
    });

    function saveHtmlContent() {

        let components = editor.getComponents();
        let style = editor.getStyle()
        let templateHtmlWithCss = editor.runCommand('gjs-get-inlined-html');

        let templateData = {
            components: components,
            style: style
        };

        $.ajax({
            url: '<?php echo admin_url("emailcanvas/update_email_template_content/" . $template_data->id); ?>',
            method: 'POST',
            data: {content: JSON.stringify(templateData), template_html_css: templateHtmlWithCss},
            success: function (response) {
                data = JSON.parse(response);

                if (data.status == 1) {
                    alert_float("success", data.message);
                } else {
                    alert_float("danger", data.message);
                    return;
                }
            },
            error: function (xhr, status, error) {
                alert('Failed');
                console.error(error);
            }
        });
    }

    function resetHtmlContent() {

        if (confirm_delete()) {
            $.ajax({
                url: '<?php echo admin_url("emailcanvas/reset_email_template_content/" . $template_data->id); ?>',
                method: 'POST',
                data: {},
                success: function (response) {
                    data = JSON.parse(response);

                    if (data.status == 1) {
                        alert_float("success", data.message);
                        editor.DomComponents.clear();
                    } else {
                        alert_float("danger", data.message);
                        return;
                    }
                },
                error: function (xhr, status, error) {
                    alert('Failed');
                    console.error(error);
                }
            });
        }
    }

    function importPreset(preset_id) {

        preset_template_content_1 = `{"components":[{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["main-body"],"attributes":{"width":"100%","height":"100%","bgcolor":"rgb(234, 236, 237)","id":"i2hq"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"iq6i"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"classes":["row"],"attributes":{"valign":"top","id":"i24r"},"components":[{"type":"cell","draggable":["tr"],"classes":["main-body-cell"],"attributes":{"id":"imd9"},"components":[{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["container"],"attributes":{"width":"90%","height":"0","id":"i2dw"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"ik22"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"ip7l"},"components":[{"type":"cell","draggable":["tr"],"classes":["container-cell"],"attributes":{"valign":"top","id":"it8r"},"components":[{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["table100","c1790"],"attributes":{"width":"100%","height":"0","id":"iajl"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"icriq"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"iyfla"},"components":[{"type":"cell","draggable":["tr"],"classes":["top-cell"],"attributes":{"id":"c1793","align":"right"},"components":[{"tagName":"u","type":"text","classes":["browser-link"],"attributes":{"id":"c307"},"components":[{"type":"textnode","content":"View in browser\\n                          "}]}]}]}]}]},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["c1766"],"attributes":{"width":"100%","id":"ig4ny"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"ij7dh"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"iuw7x"},"components":[{"type":"cell","draggable":["tr"],"classes":["cell","c1769"],"attributes":{"width":"11%","id":"irnfb"},"components":[{"type":"table","droppable":["tbody","thead","tfoot"],"attributes":{"id":"iar87z"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"components":[{"type":"cell","draggable":["tr"],"attributes":{"id":"itvnc4"},"components":[{"type":"image","resizable":{"ratioDefault":1},"attributes":{"id":"itvdq1","src":"https://lenzcreative.net/wp-content/uploads/2024/01/Untitled_design__2_-removebg-preview-1.png.webp"}}]}]}]}]}]}]}]}]},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["card"],"attributes":{"height":"0","id":"iyspe"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"imv7a"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"izn8s"},"components":[{"type":"cell","draggable":["tr"],"classes":["card-cell"],"attributes":{"bgcolor":"rgb(255, 255, 255)","align":"center","id":"i1ii1"},"components":[{"type":"image","resizable":{"ratioDefault":1},"attributes":{"id":"im1nxw","src":"https://s3.envato.com/files/488778714/lenzcreative_codecanyon_banner.png"}},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["table100","c1357"],"attributes":{"width":"100%","height":"0","id":"isbwz"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"i0f49"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"i44hh"},"components":[{"type":"cell","draggable":["tr"],"classes":["card-content"],"attributes":{"valign":"top","id":"iamfs"},"components":[{"tagName":"h1","type":"text","content":"Hi Lenzcreative","classes":["card-title"],"attributes":{"id":"i9jjo"}},{"tagName":"p","type":"text","content":"You are added as member on our CRM.<br><br>Please use the following logic credentials:<br><br><strong>Email:</strong> {staff_email}<br><strong>Password:</strong> {password}<br><br>Click <a data-cke-saved-href=\\"{admin_url}\\" href=\\"{admin_url}\\">here </a>to login in the dashboard or in the button below.<br><br>","classes":["card-text"],"attributes":{"id":"ijpu3"}},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["c1542"],"attributes":{"width":"100%","id":"i3kum"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"ifj5a"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"ixodh"},"components":[{"type":"cell","draggable":["tr"],"classes":["card-footer"],"attributes":{"id":"c1545","align":"center"},"components":[{"type":"link","content":"Login Dashboard","classes":["button"],"attributes":{"href":"#","id":"iuskp"}}]}]}]}]}]}]}]}]}]}]}]}]},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["footer"],"attributes":{"align":"center","id":"ik3e4g"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"iv96lz"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"ic0igt"},"components":[{"type":"cell","draggable":["tr"],"classes":["footer-cell"],"attributes":{"id":"i467wc"},"components":[{"classes":["c2577"],"attributes":{"id":"iw1c4t"},"components":[{"tagName":"p","type":"text","content":"<strong>LenzCreative</strong>&nbsp;is a specialized team focused on creating high-quality modules for&nbsp;<strong>Perfex CRM</strong>. With our expertise in&nbsp;<strong>Perfex CRM development</strong>, we provide tailored solutions to enhance the functionality and customization options of the CRM platform.","classes":["footer-info"],"attributes":{"id":"i6zqbw"}},{"tagName":"p","attributes":{"id":"i2p39i"},"components":[{"type":"link","content":"Lenzcreative Preset","classes":["link"],"attributes":{"href":"#","id":"ifwcnr"}},{"tagName":"br","void":true,"attributes":{"id":"i9d2ej"}}]}]}]}]}]}]}]}]}]}]}]}]}]}]}],"style":[{"selectors":["link"],"style":{"color":"rgb(217, 131, 166)"}},{"selectors":["browser-link"],"style":{"font-size":"12px"}},{"selectors":["#i2hq"],"style":{"box-sizing":"border-box","min-height":"150px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","width":"100%","height":"100%","background-color":"rgb(234, 236, 237)"}},{"selectors":["#iq6i"],"style":{"box-sizing":"border-box"}},{"selectors":["#i24r"],"style":{"box-sizing":"border-box","vertical-align":"top"}},{"selectors":["#imd9"],"style":{"box-sizing":"border-box"}},{"selectors":["#i2dw"],"style":{"box-sizing":"border-box","font-family":"Helvetica, serif","min-height":"150px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","margin-top":"auto","margin-right":"auto","margin-bottom":"auto","margin-left":"auto","height":"0px","width":"90%","max-width":"550px"}},{"selectors":["#ik22"],"style":{"box-sizing":"border-box"}},{"selectors":["#ip7l"],"style":{"box-sizing":"border-box"}},{"selectors":["#it8r"],"style":{"box-sizing":"border-box","vertical-align":"top","font-size":"medium","padding-bottom":"50px"}},{"selectors":["#iajl"],"style":{"box-sizing":"border-box","width":"100%","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","height":"0px","min-height":"30px","border-collapse":"separate","margin-top":"0px","margin-right":"0px","margin-bottom":"10px","margin-left":"0px"}},{"selectors":["#icriq"],"style":{"box-sizing":"border-box"}},{"selectors":["#iyfla"],"style":{"box-sizing":"border-box"}},{"selectors":["#c1793"],"style":{"box-sizing":"border-box","text-align":"right","color":"rgb(152, 156, 165)"}},{"selectors":["#c307"],"style":{"box-sizing":"border-box","font-size":"12px"}},{"selectors":["#ig4ny"],"style":{"box-sizing":"border-box","margin-top":"0px","margin-right":"auto","margin-bottom":"10px","margin-left":"0px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","width":"100%","min-height":"30px"}},{"selectors":["#ij7dh"],"style":{"box-sizing":"border-box"}},{"selectors":["#iuw7x"],"style":{"box-sizing":"border-box"}},{"selectors":["#irnfb"],"style":{"box-sizing":"border-box","width":"11%"}},{"selectors":["#iyspe"],"style":{"box-sizing":"border-box","min-height":"150px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","margin-bottom":"20px","height":"0px"}},{"selectors":["#imv7a"],"style":{"box-sizing":"border-box"}},{"selectors":["#izn8s"],"style":{"box-sizing":"border-box"}},{"selectors":["#i1ii1"],"style":{"box-sizing":"border-box","background-color":"rgb(255, 255, 255)","overflow-x":"hidden","overflow-y":"hidden","border-top-left-radius":"3px","border-top-right-radius":"3px","border-bottom-right-radius":"3px","border-bottom-left-radius":"3px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px","text-align":"center"}},{"selectors":["#isbwz"],"style":{"box-sizing":"border-box","width":"100%","min-height":"150px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","height":"0px","margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-collapse":"collapse"}},{"selectors":["#i0f49"],"style":{"box-sizing":"border-box"}},{"selectors":["#i44hh"],"style":{"box-sizing":"border-box"}},{"selectors":["#iamfs"],"style":{"box-sizing":"border-box","font-size":"13px","line-height":"20px","color":"rgb(111, 119, 125)","padding-top":"10px","padding-right":"20px","padding-bottom":"0px","padding-left":"20px","vertical-align":"top"}},{"selectors":["#i9jjo"],"style":{"box-sizing":"border-box","font-size":"25px","font-weight":"300","color":"rgb(68, 68, 68)","text-align":"left"}},{"selectors":["#ipq16"],"style":{"box-sizing":"border-box"}},{"selectors":["#ijpu3"],"style":{"box-sizing":"border-box","text-align":"left"}},{"selectors":["#i3kum"],"style":{"box-sizing":"border-box","margin-top":"0px","margin-right":"auto","margin-bottom":"10px","margin-left":"auto","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","width":"100%"}},{"selectors":["#ifj5a"],"style":{"box-sizing":"border-box"}},{"selectors":["#ixodh"],"style":{"box-sizing":"border-box"}},{"selectors":["#c1545"],"style":{"box-sizing":"border-box","padding-top":"20px","padding-right":"0px","padding-bottom":"20px","padding-left":"0px","text-align":"center"}},{"selectors":["#iuskp"],"style":{"box-sizing":"border-box","font-size":"12px","padding-top":"10px","padding-right":"20px","padding-bottom":"10px","padding-left":"20px","background-color":"rgb(217, 131, 166)","color":"rgb(255, 255, 255)","text-align":"center","border-top-left-radius":"3px","border-top-right-radius":"3px","border-bottom-right-radius":"3px","border-bottom-left-radius":"3px","font-weight":"300"}},{"selectors":["#ik3e4g"],"style":{"box-sizing":"border-box","margin-top":"50px","color":"rgb(152, 156, 165)","text-align":"center","font-size":"11px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px"}},{"selectors":["#iv96lz"],"style":{"box-sizing":"border-box"}},{"selectors":["#ic0igt"],"style":{"box-sizing":"border-box"}},{"selectors":["#i467wc"],"style":{"box-sizing":"border-box"}},{"selectors":["#iw1c4t"],"style":{"box-sizing":"border-box","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"}},{"selectors":["#i6zqbw"],"style":{"box-sizing":"border-box"}},{"selectors":["#i2p39i"],"style":{"box-sizing":"border-box"}},{"selectors":["#ifwcnr"],"style":{"box-sizing":"border-box","color":"rgb(217, 131, 166)"}},{"selectors":["#i9d2ej"],"style":{"box-sizing":"border-box"}},{"selectors":["#iar87z"],"style":{"height":"150px","margin":"0 auto 10px auto","padding":"5px 5px 5px 5px","width":"100%"}},{"selectors":["#itvnc4"],"style":{"font-size":"12px","font-weight":"300","vertical-align":"top","color":"rgb(111, 119, 125)","margin":"0","padding":"0","width":"33.3333%"}},{"selectors":["#itvdq1"],"style":{"color":"black","width":"100%"}},{"selectors":["#im1nxw"],"style":{"color":"black","width":"100%"}}]}`;
        preset_template_content_2 = `{"components":[{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["main-body"],"attributes":{"width":"100%","height":"100%","bgcolor":"rgb(234, 236, 237)","id":"i2hq"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"iq6i"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"classes":["row"],"attributes":{"valign":"top","id":"i24r"},"components":[{"type":"cell","draggable":["tr"],"classes":["main-body-cell"],"attributes":{"id":"imd9"},"components":[{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["container"],"attributes":{"width":"90%","height":"0","id":"i2dw"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"ik22"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"ip7l"},"components":[{"type":"cell","draggable":["tr"],"classes":["container-cell"],"attributes":{"valign":"top","id":"it8r"},"components":[{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["table100","c1790"],"attributes":{"width":"100%","height":"0","id":"iajl"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"icriq"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"iyfla"},"components":[{"type":"cell","draggable":["tr"],"classes":["top-cell"],"attributes":{"id":"c1793","align":"right"},"components":[{"tagName":"u","type":"text","classes":["browser-link"],"attributes":{"id":"c307"},"components":[{"type":"textnode","content":"View in browser\\n                          "}]}]}]}]}]},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["c1766"],"attributes":{"width":"100%","id":"ig4ny"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"ij7dh"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"iuw7x"},"components":[{"type":"cell","draggable":["tr"],"classes":["cell","c1769"],"attributes":{"width":"11%","id":"irnfb"},"components":[{"type":"image","resizable":{"ratioDefault":1},"attributes":{"id":"i76rll","src":"https://s3.envato.com/files/488778388/Untitled%20design%20(1).png"}}]},{"type":"cell","draggable":["tr"],"classes":["cell","c1776"],"attributes":{"width":"70%","valign":"middle","id":"ig8h5"},"components":[{"type":"text","content":"<p>Lenzcreative Different Elements Of Email Template</p>","classes":["c1144"],"attributes":{"id":"i39rn"}}]}]}]}]},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["card"],"attributes":{"height":"0","id":"iyspe"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"imv7a"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"izn8s"},"components":[{"type":"cell","draggable":["tr"],"classes":["card-cell"],"attributes":{"bgcolor":"rgb(255, 255, 255)","align":"center","id":"i1ii1"},"components":[{"type":"image","resizable":{"ratioDefault":1},"attributes":{"id":"i1j8rt","src":"https://s3.envato.com/files/488778714/lenzcreative_codecanyon_banner.png"}},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["table100","c1357"],"attributes":{"width":"100%","height":"0","id":"isbwz"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"i0f49"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"i44hh"},"components":[{"type":"cell","draggable":["tr"],"classes":["card-content"],"attributes":{"valign":"top","id":"iamfs"},"components":[{"tagName":"h1","type":"text","content":"Build your email templates faster than ever","classes":["card-title"],"attributes":{"id":"i9jjo"}},{"tagName":"p","type":"text","content":"Import, build, test and export responsive newsletter templates faster than ever using the Email Canvas Module.","classes":["card-text"],"attributes":{"id":"ijpu3"}},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["c1542"],"attributes":{"width":"100%","id":"i3kum"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"ifj5a"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"ixodh"},"components":[{"type":"cell","draggable":["tr"],"classes":["card-footer"],"attributes":{"id":"c1545","align":"center"},"components":[{"type":"link","content":"Button With Link","classes":["button"],"attributes":{"href":"#","id":"iuskp"}}]}]}]}]}]}]}]}]}]}]}]}]},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["list-item"],"attributes":{"width":"100%","id":"idw3i"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"itf08"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"iz3hb"},"components":[{"type":"cell","draggable":["tr"],"classes":["list-item-cell"],"attributes":{"bgcolor":"rgb(255, 255, 255)","id":"i9mnn"},"components":[{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["list-item-content"],"attributes":{"width":"100%","height":"150","id":"ij19h"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"ihwan"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"classes":["list-item-row"],"attributes":{"id":"ic46f"},"components":[{"type":"cell","draggable":["tr"],"classes":["list-cell-left"],"attributes":{"width":"30%","id":"ir3eh"},"components":[{"type":"image","resizable":{"ratioDefault":1},"attributes":{"id":"izmkbh","src":"https://s3.envato.com/files/496743254/Swiss%20QR%20Bill%20-%2080x80.png"}}]},{"type":"cell","draggable":["tr"],"classes":["list-cell-right"],"attributes":{"width":"70%","id":"iguz9"},"components":[{"tagName":"h1","type":"text","classes":["card-title"],"attributes":{"id":"ifto2"},"components":[{"type":"textnode","content":"Built-in Blocks\\n                                  "}]},{"tagName":"p","type":"text","classes":["card-text"],"attributes":{"id":"ijk4w"},"components":[{"type":"textnode","content":"Drag and drop built-in blocks from the right panel and style them in a matter of seconds\\n                                  "}]}]}]}]}]}]}]}]}]},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["list-item"],"attributes":{"width":"100%","id":"iefan"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"i1t4l"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"itj4l"},"components":[{"type":"cell","draggable":["tr"],"classes":["list-item-cell"],"attributes":{"bgcolor":"rgb(255, 255, 255)","id":"ikth7"},"components":[{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["list-item-content"],"attributes":{"width":"100%","height":"150","id":"immnh"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"iiyv4"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"classes":["list-item-row"],"attributes":{"id":"i2uex"},"components":[{"type":"cell","draggable":["tr"],"classes":["list-cell-left"],"attributes":{"width":"30%","id":"ig3iy"},"components":[{"type":"image","resizable":{"ratioDefault":1},"attributes":{"id":"izd714","src":"https://s3.envato.com/files/488319374/Predix%20-%2080x80.png"}}]},{"type":"cell","draggable":["tr"],"classes":["list-cell-right"],"attributes":{"width":"70%","id":"is1vi"},"components":[{"tagName":"h1","type":"text","classes":["card-title"],"attributes":{"id":"ip1co"},"components":[{"type":"textnode","content":"Toggle images\\n                                  "}]},{"tagName":"p","type":"text","classes":["card-text"],"attributes":{"id":"i5708"},"components":[{"type":"textnode","content":"Build a good looking newsletter even without images enabled by the email clients\\n                                  "}]}]}]}]}]}]}]}]}]},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["grid-item-row"],"attributes":{"width":"100%","id":"ieq08"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"iwvob"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"i0dbo"},"components":[{"type":"cell","draggable":["tr"],"classes":["grid-item-cell2-l"],"attributes":{"width":"50%","valign":"top","id":"i2u8k"},"components":[{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["grid-item-card"],"attributes":{"width":"100%","id":"iyba4"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"i551j"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"ibeel"},"components":[{"type":"cell","draggable":["tr"],"classes":["grid-item-card-cell"],"attributes":{"bgcolor":"rgb(255, 255, 255)","align":"center","id":"ihftf"},"components":[{"type":"image","resizable":{"ratioDefault":1},"attributes":{"id":"iczi0i","src":"https://s3.envato.com/files/490970361/DataPulse%20-%2080x80.png"}},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["grid-item-card-body"],"attributes":{"id":"iw12e"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"ickf4"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"iknhg"},"components":[{"type":"cell","draggable":["tr"],"classes":["grid-item-card-content"],"attributes":{"width":"100%","id":"i5ara"},"components":[{"tagName":"h1","type":"text","classes":["card-title"],"attributes":{"id":"i9bye"},"components":[{"type":"textnode","content":"Test it\\n                                          "}]},{"tagName":"p","type":"text","classes":["card-text"],"attributes":{"id":"ihz8f"},"components":[{"type":"textnode","content":"You can send email tests directly from the editor and check how are looking on your email clients\\n                                          "}]}]}]}]}]}]}]}]}]}]},{"type":"cell","draggable":["tr"],"classes":["grid-item-cell2-r"],"attributes":{"width":"50%","valign":"top","id":"ibrcl"},"components":[{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["grid-item-card"],"attributes":{"width":"100%","id":"ix0ef"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"iwv29"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"iaqip"},"components":[{"type":"cell","draggable":["tr"],"classes":["grid-item-card-cell"],"attributes":{"bgcolor":"rgb(255, 255, 255)","align":"center","id":"ih9cg"},"components":[{"type":"image","resizable":{"ratioDefault":1},"attributes":{"id":"ioew93","src":"https://s3.envato.com/files/488313068/ReportPlus%20-%2080x80.png"}},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["grid-item-card-body"],"attributes":{"id":"iuf0k"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"idbn3"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"iq3r8"},"components":[{"type":"cell","draggable":["tr"],"classes":["grid-item-card-content"],"attributes":{"width":"100%","id":"idz0n"},"components":[{"tagName":"h1","type":"text","classes":["card-title"],"attributes":{"id":"ika02"},"components":[{"type":"textnode","content":"Responsive\\n                                          "}]},{"tagName":"p","type":"text","classes":["card-text"],"attributes":{"id":"i838bw"},"components":[{"type":"textnode","content":"Using the device manager you'll always send a fully responsive contents\\n                                          "}]}]}]}]}]}]}]}]}]}]}]}]}]},{"type":"table","droppable":["tbody","thead","tfoot"],"classes":["footer"],"attributes":{"align":"center","id":"ik3e4g"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"attributes":{"id":"iv96lz"},"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"attributes":{"id":"ic0igt"},"components":[{"type":"cell","draggable":["tr"],"classes":["footer-cell"],"attributes":{"id":"i467wc"},"components":[{"classes":["c2577"],"attributes":{"id":"iw1c4t"},"components":[{"tagName":"p","type":"text","classes":["footer-info"],"attributes":{"id":"i6zqbw"},"components":[{"type":"textnode","content":"GrapesJS Newsletter Builder is a free and open source preset (plugin) used on top of the GrapesJS core library.\\n                              For more information about and how to integrate it inside your applications check\\n                            "}]},{"tagName":"p","attributes":{"id":"i2p39i"},"components":[{"type":"link","content":"Lenzcreative Preset","classes":["link"],"attributes":{"href":"#","id":"ifwcnr"}},{"tagName":"br","void":true,"attributes":{"id":"i9d2ej"}}]}]}]}]}]}]}]}]}]}]}]}]}]}]}],"style":[{"selectors":["link"],"style":{"color":"rgb(217, 131, 166)"}},{"selectors":["browser-link"],"style":{"font-size":"12px"}},{"selectors":["#i2hq"],"style":{"box-sizing":"border-box","min-height":"150px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","width":"100%","height":"100%","background-color":"rgb(234, 236, 237)"}},{"selectors":["#iq6i"],"style":{"box-sizing":"border-box"}},{"selectors":["#i24r"],"style":{"box-sizing":"border-box","vertical-align":"top"}},{"selectors":["#imd9"],"style":{"box-sizing":"border-box"}},{"selectors":["#i2dw"],"style":{"box-sizing":"border-box","font-family":"Helvetica, serif","min-height":"150px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","margin-top":"auto","margin-right":"auto","margin-bottom":"auto","margin-left":"auto","height":"0px","width":"90%","max-width":"550px"}},{"selectors":["#ik22"],"style":{"box-sizing":"border-box"}},{"selectors":["#ip7l"],"style":{"box-sizing":"border-box"}},{"selectors":["#it8r"],"style":{"box-sizing":"border-box","vertical-align":"top","font-size":"medium","padding-bottom":"50px"}},{"selectors":["#iajl"],"style":{"box-sizing":"border-box","width":"100%","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","height":"0px","min-height":"30px","border-collapse":"separate","margin-top":"0px","margin-right":"0px","margin-bottom":"10px","margin-left":"0px"}},{"selectors":["#icriq"],"style":{"box-sizing":"border-box"}},{"selectors":["#iyfla"],"style":{"box-sizing":"border-box"}},{"selectors":["#c1793"],"style":{"box-sizing":"border-box","text-align":"right","color":"rgb(152, 156, 165)"}},{"selectors":["#c307"],"style":{"box-sizing":"border-box","font-size":"12px"}},{"selectors":["#ig4ny"],"style":{"box-sizing":"border-box","margin-top":"0px","margin-right":"auto","margin-bottom":"10px","margin-left":"0px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","width":"100%","min-height":"30px"}},{"selectors":["#ij7dh"],"style":{"box-sizing":"border-box"}},{"selectors":["#iuw7x"],"style":{"box-sizing":"border-box"}},{"selectors":["#irnfb"],"style":{"box-sizing":"border-box","width":"11%"}},{"selectors":["#ig8h5"],"style":{"box-sizing":"border-box","width":"70%","vertical-align":"middle"}},{"selectors":["#i39rn"],"style":{"box-sizing":"border-box","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px","font-size":"17px","font-weight":"300"}},{"selectors":["#iwhxl"],"style":{"box-sizing":"border-box"}},{"selectors":["#iyspe"],"style":{"box-sizing":"border-box","min-height":"150px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","margin-bottom":"20px","height":"0px"}},{"selectors":["#imv7a"],"style":{"box-sizing":"border-box"}},{"selectors":["#izn8s"],"style":{"box-sizing":"border-box"}},{"selectors":["#i1ii1"],"style":{"box-sizing":"border-box","background-color":"rgb(255, 255, 255)","overflow-x":"hidden","overflow-y":"hidden","border-top-left-radius":"3px","border-top-right-radius":"3px","border-bottom-right-radius":"3px","border-bottom-left-radius":"3px","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px","text-align":"center"}},{"selectors":["#isbwz"],"style":{"box-sizing":"border-box","width":"100%","min-height":"150px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","height":"0px","margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px","border-collapse":"collapse"}},{"selectors":["#i0f49"],"style":{"box-sizing":"border-box"}},{"selectors":["#i44hh"],"style":{"box-sizing":"border-box"}},{"selectors":["#iamfs"],"style":{"box-sizing":"border-box","font-size":"13px","line-height":"20px","color":"rgb(111, 119, 125)","padding-top":"10px","padding-right":"20px","padding-bottom":"0px","padding-left":"20px","vertical-align":"top"}},{"selectors":["#i9jjo"],"style":{"box-sizing":"border-box","font-size":"25px","font-weight":"300","color":"rgb(68, 68, 68)"}},{"selectors":["#ipq16"],"style":{"box-sizing":"border-box"}},{"selectors":["#ijpu3"],"style":{"box-sizing":"border-box"}},{"selectors":["#i3kum"],"style":{"box-sizing":"border-box","margin-top":"0px","margin-right":"auto","margin-bottom":"10px","margin-left":"auto","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","width":"100%"}},{"selectors":["#ifj5a"],"style":{"box-sizing":"border-box"}},{"selectors":["#ixodh"],"style":{"box-sizing":"border-box"}},{"selectors":["#c1545"],"style":{"box-sizing":"border-box","padding-top":"20px","padding-right":"0px","padding-bottom":"20px","padding-left":"0px","text-align":"center"}},{"selectors":["#iuskp"],"style":{"box-sizing":"border-box","font-size":"12px","padding-top":"10px","padding-right":"20px","padding-bottom":"10px","padding-left":"20px","background-color":"rgb(217, 131, 166)","color":"rgb(255, 255, 255)","text-align":"center","border-top-left-radius":"3px","border-top-right-radius":"3px","border-bottom-right-radius":"3px","border-bottom-left-radius":"3px","font-weight":"300"}},{"selectors":["#idw3i"],"style":{"box-sizing":"border-box","height":"auto","width":"100%","margin-top":"0px","margin-right":"auto","margin-bottom":"10px","margin-left":"auto","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px"}},{"selectors":["#itf08"],"style":{"box-sizing":"border-box"}},{"selectors":["#iz3hb"],"style":{"box-sizing":"border-box"}},{"selectors":["#i9mnn"],"style":{"box-sizing":"border-box","background-color":"rgb(255, 255, 255)","border-top-left-radius":"3px","border-top-right-radius":"3px","border-bottom-right-radius":"3px","border-bottom-left-radius":"3px","overflow-x":"hidden","overflow-y":"hidden","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"}},{"selectors":["#ij19h"],"style":{"box-sizing":"border-box","border-collapse":"collapse","margin-top":"0px","margin-right":"auto","margin-bottom":"0px","margin-left":"auto","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","height":"150px","width":"100%"}},{"selectors":["#ihwan"],"style":{"box-sizing":"border-box"}},{"selectors":["#ic46f"],"style":{"box-sizing":"border-box"}},{"selectors":["#ir3eh"],"style":{"box-sizing":"border-box","width":"30%","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"}},{"selectors":["#iguz9"],"style":{"box-sizing":"border-box","width":"70%","color":"rgb(111, 119, 125)","font-size":"13px","line-height":"20px","padding-top":"10px","padding-right":"20px","padding-bottom":"0px","padding-left":"20px"}},{"selectors":["#ifto2"],"style":{"box-sizing":"border-box","font-size":"25px","font-weight":"300","color":"rgb(68, 68, 68)"}},{"selectors":["#ijk4w"],"style":{"box-sizing":"border-box"}},{"selectors":["#iefan"],"style":{"box-sizing":"border-box","height":"auto","width":"100%","margin-top":"0px","margin-right":"auto","margin-bottom":"10px","margin-left":"auto","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px"}},{"selectors":["#i1t4l"],"style":{"box-sizing":"border-box"}},{"selectors":["#itj4l"],"style":{"box-sizing":"border-box"}},{"selectors":["#ikth7"],"style":{"box-sizing":"border-box","background-color":"rgb(255, 255, 255)","border-top-left-radius":"3px","border-top-right-radius":"3px","border-bottom-right-radius":"3px","border-bottom-left-radius":"3px","overflow-x":"hidden","overflow-y":"hidden","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"}},{"selectors":["#immnh"],"style":{"box-sizing":"border-box","border-collapse":"collapse","margin-top":"0px","margin-right":"auto","margin-bottom":"0px","margin-left":"auto","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px","height":"150px","width":"100%"}},{"selectors":["#iiyv4"],"style":{"box-sizing":"border-box"}},{"selectors":["#i2uex"],"style":{"box-sizing":"border-box"}},{"selectors":["#ig3iy"],"style":{"box-sizing":"border-box","width":"30%","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"}},{"selectors":["#is1vi"],"style":{"box-sizing":"border-box","width":"70%","color":"rgb(111, 119, 125)","font-size":"13px","line-height":"20px","padding-top":"10px","padding-right":"20px","padding-bottom":"0px","padding-left":"20px"}},{"selectors":["#ip1co"],"style":{"box-sizing":"border-box","font-size":"25px","font-weight":"300","color":"rgb(68, 68, 68)"}},{"selectors":["#i5708"],"style":{"box-sizing":"border-box"}},{"selectors":["#ieq08"],"style":{"box-sizing":"border-box","margin-top":"0px","margin-right":"auto","margin-bottom":"10px","margin-left":"auto","padding-top":"5px","padding-right":"0px","padding-bottom":"5px","padding-left":"0px","width":"100%"}},{"selectors":["#iwvob"],"style":{"box-sizing":"border-box"}},{"selectors":["#i0dbo"],"style":{"box-sizing":"border-box"}},{"selectors":["#i2u8k"],"style":{"box-sizing":"border-box","vertical-align":"top","padding-right":"10px","width":"50%"}},{"selectors":["#iyba4"],"style":{"box-sizing":"border-box","width":"100%","padding-top":"5px","padding-right":"0px","padding-bottom":"5px","padding-left":"0px","margin-bottom":"10px"}},{"selectors":["#i551j"],"style":{"box-sizing":"border-box"}},{"selectors":["#ibeel"],"style":{"box-sizing":"border-box"}},{"selectors":["#ihftf"],"style":{"box-sizing":"border-box","background-color":"rgb(255, 255, 255)","overflow-x":"hidden","overflow-y":"hidden","border-top-left-radius":"3px","border-top-right-radius":"3px","border-bottom-right-radius":"3px","border-bottom-left-radius":"3px","text-align":"center","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"}},{"selectors":["#iw12e"],"style":{"box-sizing":"border-box"}},{"selectors":["#ickf4"],"style":{"box-sizing":"border-box"}},{"selectors":["#iknhg"],"style":{"box-sizing":"border-box"}},{"selectors":["#i5ara"],"style":{"box-sizing":"border-box","font-size":"13px","color":"rgb(111, 119, 125)","padding-top":"0px","padding-right":"10px","padding-bottom":"20px","padding-left":"10px","width":"100%","line-height":"20px"}},{"selectors":["#i9bye"],"style":{"box-sizing":"border-box","font-size":"25px","font-weight":"300","color":"rgb(68, 68, 68)"}},{"selectors":["#ihz8f"],"style":{"box-sizing":"border-box"}},{"selectors":["#ibrcl"],"style":{"box-sizing":"border-box","vertical-align":"top","padding-left":"10px","width":"50%"}},{"selectors":["#ix0ef"],"style":{"box-sizing":"border-box","width":"100%","padding-top":"5px","padding-right":"0px","padding-bottom":"5px","padding-left":"0px","margin-bottom":"10px"}},{"selectors":["#iwv29"],"style":{"box-sizing":"border-box"}},{"selectors":["#iaqip"],"style":{"box-sizing":"border-box"}},{"selectors":["#ih9cg"],"style":{"box-sizing":"border-box","background-color":"rgb(255, 255, 255)","overflow-x":"hidden","overflow-y":"hidden","border-top-left-radius":"3px","border-top-right-radius":"3px","border-bottom-right-radius":"3px","border-bottom-left-radius":"3px","text-align":"center","padding-top":"0px","padding-right":"0px","padding-bottom":"0px","padding-left":"0px"}},{"selectors":["#iuf0k"],"style":{"box-sizing":"border-box"}},{"selectors":["#idbn3"],"style":{"box-sizing":"border-box"}},{"selectors":["#iq3r8"],"style":{"box-sizing":"border-box"}},{"selectors":["#idz0n"],"style":{"box-sizing":"border-box","font-size":"13px","color":"rgb(111, 119, 125)","padding-top":"0px","padding-right":"10px","padding-bottom":"20px","padding-left":"10px","width":"100%","line-height":"20px"}},{"selectors":["#ika02"],"style":{"box-sizing":"border-box","font-size":"25px","font-weight":"300","color":"rgb(68, 68, 68)"}},{"selectors":["#i838bw"],"style":{"box-sizing":"border-box"}},{"selectors":["#ik3e4g"],"style":{"box-sizing":"border-box","margin-top":"50px","color":"rgb(152, 156, 165)","text-align":"center","font-size":"11px","padding-top":"5px","padding-right":"5px","padding-bottom":"5px","padding-left":"5px"}},{"selectors":["#iv96lz"],"style":{"box-sizing":"border-box"}},{"selectors":["#ic0igt"],"style":{"box-sizing":"border-box"}},{"selectors":["#i467wc"],"style":{"box-sizing":"border-box"}},{"selectors":["#iw1c4t"],"style":{"box-sizing":"border-box","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"}},{"selectors":["#i6zqbw"],"style":{"box-sizing":"border-box"}},{"selectors":["#i2p39i"],"style":{"box-sizing":"border-box"}},{"selectors":["#ifwcnr"],"style":{"box-sizing":"border-box","color":"rgb(217, 131, 166)"}},{"selectors":["#i9d2ej"],"style":{"box-sizing":"border-box"}},{"selectors":["#izmkbh"],"style":{"color":"black","width":"100%"}},{"selectors":["#i1j8rt"],"style":{"color":"black","width":"100%"}},{"selectors":["#i76rll"],"style":{"color":"black"}},{"selectors":["#izd714"],"style":{"color":"black","width":"100%"}},{"selectors":["#iczi0i"],"style":{"color":"black","width":"100%"}},{"selectors":["#ioew93"],"style":{"color":"black","width":"100%"}}]}`;

        if (confirm_delete()) {
            if (preset_id == 1) {
                templateData = JSON.parse(preset_template_content_1);
            }

            if (preset_id == 2) {
                templateData = JSON.parse(preset_template_content_2);
            }

            editor.setComponents(templateData.components);
            editor.setStyle(templateData.style);
        }
    }

    function testEmailTemplate() {
        var email = $('input[name="test_email"]').val();

        if (email != '') {

            $(this).attr('disabled', true);

            $.post(admin_url + 'emailcanvas/send_email_template_test/<?php echo $template_data->id; ?>', {
                test_email: email
            }).done(function (data) {
                window.location.reload();
            });
        }

    }

</script>
</body>

</html>