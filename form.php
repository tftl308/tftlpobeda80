<?php
/*
Template Name: Страница формы
*/
get_header('single');

?>
    <style>
        .main-section {
            height: auto;
        }

        h1 {
            margin-bottom: 20px;
        }

        .photo-option {
            width: 100px;
            height: 125px;
            cursor: pointer;
            border: 2px solid transparent;
            object-fit: fill;
        }

        .photo-option.selected {
            border-color: #dc4128;
        }

        .file-upload {
            position: relative;
            display: flex;
            align-items: center;
            cursor: pointer;
            border: 2px dashed #dc4128;
            border-radius: 5px;
            padding: 0 20px;
            text-align: center;
            color: #dc4128;
            font-weight: bold;
            transition: background-color 0.3s;
            height: 65px;
        }

        .file-upload:hover {
            background-color: #fff0f0;
        }

        .file-upload input[type="file"] {
            display: none;
        }

        .card-preview-wrapper {
            width: 430px;
            flex-shrink: 0;
            position: sticky;
            top: 124px;
            height: fit-content;
            border-style: solid;
            border-color: #C2351F;
            border-width: 0;
            border-left-width: 1px;
            padding-left: 20px;
            box-sizing: content-box;
        }
        .card-preview-title {
            font-weight: 500;
            margin-top: 16px;
            margin-bottom: 12px;
        }
        .preview-popup {
            height: calc(100vh - 400px);
            position: relative;
        }
        .preview-popup * {
            cursor: default !important;
        }
        .preview-popup .profile-popup-top {
            bottom: 70%;
            opacity: 1;
        }
        .preview-popup .profile-popup-bottom {
            top: 30%;
            padding-bottom: 0;
        }
        .preview-list-card {
            cursor: default !important;
        }

        @media (max-width: 1024px) {
            .form-wrapper {
                flex-direction: column;
            }

            .card-preview-wrapper {
                width: auto;
                position: static;
                border-left-width: 0;
                border-top-width: 1px;
                padding-left: 0;
                padding-top: 20px;
            }

            .preview-popup {
                height: 800px;
            }
        }
    </style>
    <a class="header-home-btn" href="<?php echo home_url(); ?>"
       onclick="$(this).children('span').css({transform: 'translateX(-200px)'});">
        <i class="far fa-arrow-left"></i>
        <span>На главную</span>
    </a>

    <div class="form-wrapper">
        <div class="form">
            <h1>Форма</h1>
            <form class="form-content" id="applicationForm">
                <div id="formResponse" class="form-response">
                    <p class="response-text"></p>
                    <button type="button" class="response-close">Закрыть</button>
                </div>
                <div class="form-section">
                    <label class="form-item">
                        <input name="fio" type="text" class="required" placeholder="Иванов Иван Иванович">
                        <span class="description">Введите ФИО</span>
                    </label>
                </div>
                <div class="form-section photo">
                <span class="description">
                    Выберите фото героя / Загрузите свое
                </span>
                    <div id="photo-options">
                        <img src="<?=get_template_directory_uri()?>/img/photo1.jpg" class="photo-option" onclick="selectPhoto(this)" alt="">
                        <img src="<?=get_template_directory_uri()?>/img/photo2.jpg" class="photo-option" onclick="selectPhoto(this)">
                        <img src="<?=get_template_directory_uri()?>/img/photo3.jpg" class="photo-option" onclick="selectPhoto(this)">
                        <img src="<?=get_template_directory_uri()?>/img/photo4.jpg" class="photo-option" onclick="selectPhoto(this)">
                        <canvas id="canvas"></canvas>
                        <div id="remove-photo" style="display: none; cursor: pointer;" onclick="removeCustomPhoto()">✖</div>
                    </div>

                    <div class="custom-photo" style="align-items: center">
                        <label class="file-upload">
                            <span>Выберите фото</span>
                            <input name="photo" type="file" id="upload" accept="image/*" onchange="handleFileUpload(event)">
                        </label>
                        <div id="single-file-info"></div>
                    </div>
                    <div id="single-error-message" style="color: #DC4128"></div>

                </div>
                <label class="form-item">
                    <input name="attitude" type="text" class="required" placeholder="Прадедушка ученика Иванова Ивана \ бабушка учителя химии Ивановой Инны Ивановны">
                    <span class="description">Отношение к Образовательному учреждению</span>
                </label>
                <label class="form-item">
                    <input name="years" type="text" class="required" placeholder="19xx - настоящие время">
                    <span class="description">Годы жизни</span>
                </label>
                <label class="form-item">
                    <input name="rank" type="text" class="required" placeholder="Сержант / труженник тыла">
                    <span class="description">Воинское звание / труженник тыла / житель блокадного Ленинграда</span>
                </label>
                <label class="form-item">
                    <input name="position" type="text" class="required" placeholder="Командиров средних танков / инженер завода / доктор медицинских наук">
                    <span class="description">Воинская должность / профессия / ученая степень</span>
                </label>

                <div id="map" style="width: 100%; height: 400px;"></div>

                <div class="form-map">
                    <label for="description">Почему эта метка?</label>
                    <textarea name="label" id="description" rows="4" cols="50" placeholder="Введите описание..."></textarea>
                    <button type="button" id="addMarker">Добавить метку</button>
                </div>

                <label class="form-item">
                    <input name="link" type="text" placeholder="Укажите ссылку">
                    <span class="description">Ссылка на карточку в Памяти народа / Дороже жизни / РуВики</span>
                </label>
                <label class="form-item">
                    <textarea name="awards"  id="" cols="30" rows="10" placeholder="Укажите награды"></textarea>
                    <span class="description">Награды</span>
                </label>
                <label class="form-item">
                    <textarea name="history" id="" cols="30" rows="10" placeholder="Расскажите историю"></textarea>
                    <span class="description">История солдата</span>
                </label>
                <div id="uploaded-photos" style="display: flex; flex-wrap: wrap; margin-top: 10px;">

                </div>
                <div class="custom-photo">
                    <label class="file-upload">
                        <span>Загрузить доп.фотографии</span>
                        <input name="photos[]" multiple='multiple' type="file" id="upload2" accept="image/*" onchange="handleFileUpload2(event)">
                    </label>
                    <div id="file-info"></div>
                </div>

                <label class="form-item checkbox">
                    <input type="checkbox" class="required">
                    Подтверждаю что все введённые данные верные и не содержат оскорблений и нарушений законов РФ
                </label>

                <div id="error-message" style="color: red; display: none;"></div>

                <?php echo do_shortcode('[bws_captcha]'); ?>

                <label class="form-item form-map">
                    <button type="submit">
                        Отправить заявку
                    </button>
                </label>
            </form>
        </div>

        <div class="card-preview-wrapper">
            <div class="card-preview-box">
                <div class="card-preview-title">Предпросмотр карточки в списке</div>
                <div class="s-profile-box preview-list-card">
                    <div class="s-profile-photo" id="previewListPhoto"></div>
                    <div class="s-profile-info">
                        <div class="s-profile-info-title" id="previewListTitle">Фамилия Имя Отчество</div>
                        <div class="s-profile-info-subtitle" id="previewListSubtitle">Звание</div>
                        <div class="s-profile-info-att" id="previewListAttitude">Отношение к учреждению</div>
                        <div class="s-profile-info-years" id="previewListYears">Годы жизни</div>
                        <div class="s-profile-info-post" id="previewListPost">Воинское звание</div>
                        <div class="s-profile-info-station" id="previewListStation">Воинская должность / профессия / ученая степень</div>
                        <div class="s-profile-info-description" id="previewListDescription">Почему эта метка</div>
                        <div class="s-profile-info-rewards" id="previewListRewards">Награды</div>
                        <div class="s-profile-info-link" id="previewListLink">Ссылка</div>
                    </div>
                </div>
            </div>

            <div class="card-preview-box">
                <div class="card-preview-title">Предпросмотр всплывающей карточки</div>
                <div class="preview-popup">
                    <div class="profile-popup-top">
                        <div class="profile-popup-top-bg" id="previewPopupBg"></div>
                        <div class="profile-popup-top-photo" id="previewPopupPhoto"></div>
                    </div>
                    <div class="profile-popup-bottom">
                        <div class="profile-popup-bottom-content">
                            <div class="s-profile-info-block">
                                <div class="s-profile-info-title" id="previewPopupTitle">Фамилия Имя Отчество</div>
                                <div class="s-profile-info-subtitle" id="previewPopupSubtitle">Звание</div>
                            </div>
                            <div class="s-profile-info-block">
                                <div class="s-profile-info-block-name"><span>Отношение к учреждению</span></div>
                                <div class="s-profile-info-block-content attitude" id="previewPopupAttitude">Отношение к учреждению</div>
                            </div>
                            <div class="s-profile-info-block">
                                <div class="s-profile-info-block-name"><span>Годы жизни</span></div>
                                <div class="s-profile-info-block-content primary years" id="previewPopupYears">Годы жизни</div>
                            </div>
                            <div class="s-profile-info-block">
                                <div class="s-profile-info-block-name"><span>Воинское звание / труженик тыла / блокадник</span></div>
                                <div class="s-profile-info-block-content primary post" id="previewPopupPost">Воинское звание / труженик тыла / блокадник</div>
                            </div>
                            <div class="s-profile-info-block">
                                <div class="s-profile-info-block-name"><span>Воинская должность / профессия / ученая степень</span></div>
                                <div class="s-profile-info-block-content primary station" id="previewPopupStation">Воинская должность / профессия / ученая степень</div>
                            </div>
                            <div class="s-profile-info-block">
                                <div class="s-profile-info-block-name"><span>Награды</span></div>
                                <div class="s-profile-info-block-content primary rewards" id="previewPopupRewards">Награды</div>
                            </div>
                            <div class="s-profile-info-block">
                                <div class="s-profile-info-block-name"><span>Ссылка на Память народа / Рувики / Дорогу памяти</span></div>
                                <div class="s-profile-info-block-content primary link" id="previewPopupLink">Ссылка на Память народа / Рувики / Дорогу памяти</div>
                            </div>
                            <div class="s-profile-info-block">
                                <div class="s-profile-info-block-name"><span>Почему эта метка</span></div>
                                <div class="s-profile-info-block-content description" id="previewPopupDescription">Почему эта метка</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let customPhotoUploaded = false;
        let selectedPhoto = null;

        function getPreviewValue(selector, fallbackText = '-') {
            const field = document.querySelector(selector);

            if (!field) {
                return fallbackText;
            }

            const value = field.value ? field.value.trim() : '';

            return value || fallbackText;
        }

        function nl2br(text) {
            return text.replace(/\n/g, '<br>');
        }

        function setPreviewPhoto(photoUrl) {
            const previewElements = [
                document.getElementById('previewListPhoto'),
                document.getElementById('previewPopupBg'),
                document.getElementById('previewPopupPhoto')
            ];

            previewElements.forEach(function(element) {
                if (!element) {
                    return;
                }

                if (photoUrl) {
                    element.style.backgroundImage = 'url("' + photoUrl + '")';
                } else {
                    element.style.backgroundImage = '';
                }
            });
        }

        function updateCardPreview() {
            const fio = getPreviewValue('input[name="fio"]', 'Фамилия Имя Отчество');
            const attitude = getPreviewValue('input[name="attitude"]');
            const years = getPreviewValue('input[name="years"]');
            const rank = getPreviewValue('input[name="rank"]', 'Звание');
            const position = getPreviewValue('input[name="position"]');
            const label = getPreviewValue('textarea[name="label"]');
            const link = getPreviewValue('input[name="link"]');
            const awards = getPreviewValue('textarea[name="awards"]');

            const listMap = {
                previewListTitle: fio,
                previewListSubtitle: rank,
                previewListAttitude: attitude,
                previewListYears: years,
                previewListPost: rank,
                previewListStation: position,
                previewListDescription: label,
                previewListRewards: awards,
                previewListLink: link
            };

            const popupMap = {
                previewPopupTitle: fio,
                previewPopupSubtitle: rank,
                previewPopupAttitude: attitude,
                previewPopupYears: years,
                previewPopupPost: rank,
                previewPopupStation: position,
                previewPopupDescription: label,
                previewPopupRewards: awards,
                previewPopupLink: link
            };

            Object.keys(listMap).forEach(function(id) {
                const element = document.getElementById(id);

                if (element) {
                    element.innerHTML = nl2br(listMap[id]);
                }
            });

            Object.keys(popupMap).forEach(function(id) {
                const element = document.getElementById(id);

                if (element) {
                    element.innerHTML = nl2br(popupMap[id]);
                }
            });

            setPreviewPhoto(selectedPhoto);
        }

        function bindPreviewEvents() {
            const previewFields = document.querySelectorAll(
                'input[name="fio"], input[name="attitude"], input[name="years"], input[name="rank"], input[name="position"], input[name="link"], textarea[name="label"], textarea[name="awards"]'
            );

            previewFields.forEach(function(field) {
                field.addEventListener('input', updateCardPreview);
                field.addEventListener('change', updateCardPreview);
            });
        }

        function selectPhoto(img) {
            if (customPhotoUploaded) {
                alert("Вы уже загрузили собственное фото. Пожалуйста, удалите его, чтобы выбрать стандартное.");
                return;
            }

            const options = document.querySelectorAll('.photo-option');
            options.forEach(option => option.classList.remove('selected'));
            img.classList.add('selected');
            selectedPhoto = img.src;
            updateCardPreview();
        }


        function handleFileUpload(event) {
            let file = event.target.files[0];
            const fileInfoContainer = document.getElementById('single-file-info');
            const errorBlock = document.getElementById('single-error-message');
            let totalSize = 0;
            const maxSize = 3.5 * 1024 * 1024;

           totalSize = file.size

            if (totalSize > maxSize) {
                document.getElementById('upload').value = '';
                fileInfoContainer.innerHTML = '';
                errorBlock.style.display = 'block'
                errorBlock.innerHTML = 'Размер загружаемого файла не должен превышать 3.5 МБ.';
                return;
            }

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.src = e.target.result;
                    img.onload = function() {
                        const canvas = document.getElementById('canvas');
                        const ctx = canvas.getContext('2d');
                        canvas.width = 100;
                        canvas.height = 125;
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        canvas.classList.add('show');
                        selectedPhoto = e.target.result;
                        customPhotoUploaded = true;
                        document.getElementById('remove-photo').style.display = 'inline-block';
                        const options = document.querySelectorAll('.photo-option');
                        options.forEach(option => option.classList.remove('selected'));
                        updateCardPreview();
                    };
                };
                reader.readAsDataURL(file);
            }
            updateFileInfo(totalSize, file, fileInfoContainer);
        }

        function removeCustomPhoto() {
            const fileInfoContainer = document.getElementById('single-file-info');
            customPhotoUploaded = false;
            selectedPhoto = null;
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            canvas.classList.remove('show');
            document.getElementById('upload').value = '';
            document.getElementById('remove-photo').style.display = 'none';
            fileInfoContainer.innerHTML = ''
            updateCardPreview();
        }

        function handleFileUpload2(event) {
            const files = event.target.files;
            const uploadedPhotosContainer = document.getElementById('uploaded-photos');
            const fileInfoContainer = document.getElementById('file-info');
            const errorBlock = document.getElementById('error-message')
            let totalSize = 0;

            uploadedPhotosContainer.innerHTML = '';

            const maxSize = 3.5 * 1024 * 1024;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                totalSize += file.size;
            }

            if (totalSize > maxSize) {
                fileInfoContainer.innerHTML = '';
                errorBlock.style.display = 'block'
                errorBlock.innerHTML = 'Общий размер загружаемых файлов не должен превышать 3.5 МБ.';
                return;
            }

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.style.position = 'relative';
                    imgContainer.style.marginRight = '25px';

                    const img = new Image();
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.height = '100px';

                    const removeButton = document.createElement('div');
                    removeButton.innerHTML = '✖';
                    removeButton.style.position = 'absolute';
                    removeButton.style.top = '0';
                    removeButton.style.right = '-20px';
                    removeButton.style.cursor = 'pointer';
                    removeButton.style.color = 'black';
                    removeButton.style.fontSize = '16px';
                    removeButton.onclick = function() {
                        uploadedPhotosContainer.removeChild(imgContainer);
                        updateFileInfo();
                    };

                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeButton);
                    uploadedPhotosContainer.appendChild(imgContainer);
                };

                reader.readAsDataURL(file);
            }

            updateFileInfo(totalSize, files, fileInfoContainer);
        }

        function updateFileInfo(totalSize, files, fileInfoContainer) {
            const totalSizeInMB = (totalSize / (1024 * 1024)).toFixed(2);
            fileInfoContainer.innerHTML = `Общий размер файлов: ${totalSizeInMB} МБ<br>`;
        }

        let map;
        let placemark;
        let coordsValue;
        let descriptionValue;

        function init() {
            map = new ymaps.Map("map", {
                center: [55.7522, 37.6156],
                zoom: 10
            });

            const searchControl = new ymaps.control.SearchControl({
                options: {
                    position: { right: 10, top: 10 }
                }
            });

            map.controls.add(searchControl);

            map.events.add('click', function (e) {
                const coords = e.get('coords');
                coordsValue = coords;

                const descField = document.getElementById('description');
                const descText = descField ? descField.value : 'Нет описания';
                descriptionValue = descText


                if (placemark) {
                    placemark.geometry.setCoordinates(coords);
                    placemark.options.set('balloonContent', 'Описание: ' + descText);

                } else {
                    placemark = new ymaps.Placemark(coords, {
                        balloonContent: 'Описание: ' + descText
                    });
                    map.geoObjects.add(placemark);
                }
            });
        }

        const addMarkerBtn = document.getElementById('addMarker');
        if (addMarkerBtn) {
            addMarkerBtn.onclick = function () {
                if (placemark) {
                    const descriptionField = document.getElementById('description');
                    const description = descriptionField ? descriptionField.value : '';
                    placemark.options.set('balloonContent', 'Описание: ' + description);
                    placemark.balloon.open();
                } else {
                    alert('Сначала выберите место на карте!');
                }
            };
        }

        ymaps.ready(init);

        document.addEventListener('DOMContentLoaded', function() {
            bindPreviewEvents();
            updateCardPreview();
        });

        document.getElementById('applicationForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const inputs = this.querySelectorAll('input.required');
            let isValid = true;
            let errorMessage = document.getElementById('error-message');

            inputs.forEach(input => {
                input.style.border = '';
            });

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.style.border = '2px solid red';
                    isValid = false;
                }
            });

            if (!isValid) {
                const errorMessage = document.getElementById('error-message');
                errorMessage.textContent = 'Пожалуйста, заполните все обязательные поля или поставьте прочерк.';
                errorMessage.style.display = 'block';
                return;
            }

            const checkbox = this.querySelector('input[type="checkbox"].required');
            if (!checkbox.checked) {
                isValid = false;
                errorMessage.textContent = 'Пожалуйста, подтвердите, что все введённые данные верные.';
                errorMessage.style.display = 'block';
                return;
            }

            const captchaResult = this.querySelector('input[name="cptch_result"]');
            if (!captchaResult.value.trim()) {
                captchaResult.style.border = '2px solid red';
                isValid = false;
                document.getElementById('error-message').textContent = 'Пожалуйста, выполните капчу.';
                document.getElementById('error-message').style.display = 'block';
                return;
            }


            const formData = new FormData(this);
            formData.append('coords', coordsValue);
            formData.append('descriptionCoords', descriptionValue);
            formData.append('action', 'submit_application');

            if (selectedPhoto) {
                formData.append('selected_photo', selectedPhoto);
            }

            const formClose = document.querySelector('.response-close');

            formClose.addEventListener('click', () => {
                document.getElementById('formResponse').classList.remove('open');
                location.reload();
            });

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('formResponse').classList.add('open');
                    document.querySelector('.response-text').innerText = data.data.message || 'Произошла ошибка, попробуйте еще раз';
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    document.querySelector('.response-text').innerText = 'Произошла ошибка, попробуйте позже';
                });
        });
    </script>
<?php
get_footer();
