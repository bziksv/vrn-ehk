<style>
    
    /* Modal Overlay */
    .delivery-popup-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: none;
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .delivery-popup-modal.active {
        display: flex;
    }

    .delivery-popup-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        cursor: pointer;
    }

    /* Modal Content */
    .delivery-popup-content {
        position: relative;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        padding: 40px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Close Button */
    .delivery-popup-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        font-size: 32px;
        color: #999;
        cursor: pointer;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.3s ease;
    }

    .delivery-popup-close:hover {
        color: #333;
    }

    /* Title */
    .delivery-popup-title {
        font-size: 28px;
        font-weight: bold;
        color: #333;
        margin: 0 0 20px 0;
        text-align: center;
    }

    /* Subtitle */
    .delivery-popup-subtitle {
        font-size: 16px;
        font-weight: 600;
        color: #555;
        margin: 0 0 15px 0;
    }

    /* Body */
    .delivery-popup-body {
        margin-bottom: 20px;
    }

    /* Cities List */
    .delivery-cities-list {
        margin: 0 0 20px 0;
        transition: all 0.3s ease;
    }

    .delivery-cities {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 20px;
        list-style: none;
        padding: 0;
        margin: 0 0 20px 0;
    }

    .delivery-cities li {
        padding: 8px 12px;
        background: #f9f9f9;
        border-radius: 2px;
        color: #333;
        font-size: 14px;
		margin-left: 0px;
    }

    .delivery-cities li:hover {
        background: #f0f7ff;
    }

    /* Toggle Button */
    .delivery-popup-toggle-btn {
        display: block;
        width: 100%;
        padding: 12px 20px;
        background: #a64221;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
        margin-top: 20px;
    }

    .delivery-popup-toggle-btn:hover {
        background: #a64221db;
    }

    .delivery-popup-toggle-btn.collapsed {
        background: #666;
    }

    .delivery-popup-toggle-btn.collapsed:hover {
        background: #555;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .delivery-popup-content {
            padding: 30px 20px;
            width: 95%;
            max-height: 90vh;
        }

        .delivery-popup-title {
            font-size: 22px;
        }

        .delivery-cities {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .delivery-popup-trigger-container {
            padding: 30px 10px;
        }

        .delivery-popup-trigger {
            font-size: 14px;
            padding: 8px 16px;
        }
    }

    @media (max-width: 480px) {
        .delivery-popup-content {
            padding: 20px 15px;
            border-radius: 4px;
        }

        .delivery-popup-close {
            top: 10px;
            right: 10px;
            font-size: 28px;
        }

        .delivery-popup-title {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .delivery-popup-subtitle {
            font-size: 14px;
        }

        .delivery-cities li {
            font-size: 13px;
            padding: 6px 10px;
        }
    }
</style>

<div style="display: flex;align-items: end;gap: 5px;">
	<a href="" class="delivery-popup-trigger"><img src="/upload/ikonka-lokacii.svg" style="width: 35px; margin-right: 5px;" alt="Доставка"></a>
	<div>Доставка кованых изделий по всей РФ</div>
</div>

<div id="delivery-popup" class="delivery-popup-modal">
	<div class="delivery-popup-overlay"></div>
	<div class="delivery-popup-content">
		<button class="delivery-popup-close" aria-label="Закрыть окно">&times;</button>
		
		<div class="delivery-popup-title">Доставка по всей России</div>
		
		<div class="delivery-popup-body">
			<div class="delivery-popup-subtitle">Популярные направления:</div>
			
			<!-- Popular Cities List (visible by default) -->
			<div class="delivery-cities-list" id="popular-cities">
				<ul class="delivery-cities">
					<li>Воронеж</li>
					<li>Москва</li>
					<li>Санкт-Петербург</li>
					<li>Новосибирск</li>
					<li>Екатеринбург</li>
					<li>Казань</li>
					<li>Краснодар</li>
					<li>Ростов-на-Дону</li>
					<li>Нижний Новгород</li>
					<li>Липецк</li>
				</ul>
			</div>
			
			<!-- Full Cities List (hidden by default) -->
			<div class="delivery-cities-list" id="full-cities" style="display: none;">
				<ul class="delivery-cities">
					<li>Астрахань</li>
					<li>Белгород</li>
					<li>Брянск</li>
					<li>Великий Новгород</li>
					<li>Владимир</li>
					<li>Вологда</li>
					<li>Волгоград</li>
					<li>Иваново</li>
					<li>Ижевск</li>
					<li>Иркутск</li>
					<li>Калуга</li>
					<li>Кемерово</li>
					<li>Киров</li>
					<li>Кострома</li>
					<li>Курган</li>
					<li>Курск</li>
					<li>Махачкала</li>
					<li>Нальчик</li>
					<li>Омск</li>
					<li>Орёл</li>
					<li>Оренбург</li>
					<li>Пенза</li>
					<li>Пермь</li>
					<li>Псков</li>
					<li>Рязань</li>
					<li>Самара</li>
					<li>Саранск</li>
					<li>Саратов</li>
					<li>Смоленск</li>
					<li>Ставрополь</li>
					<li>Тамбов</li>
					<li>Тверь</li>
					<li>Тула</li>
					<li>Тюмень</li>
					<li>Ульяновск</li>
					<li>Урюпинск</li>
					<li>Уфа</li>
					<li>Челябинск</li>
					<li>Черкесск</li>
					<li>Элиста</li>
					<li>Ярославль</li>
					<li>Республика Крым</li>
					<li>Чеченская Республика</li>
					<li>Республика Северная Осетия</li>
				</ul>
			</div>
			
			<button class="delivery-popup-toggle-btn" id="toggle-cities-btn">Показать полный список городов</button>
		</div>
	</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('delivery-popup');
        const overlay = document.querySelector('.delivery-popup-overlay');
        const closeBtn = document.querySelector('.delivery-popup-close');
        const trigger = document.querySelector('.delivery-popup-trigger');
        const toggleBtn = document.getElementById('toggle-cities-btn');
        const popularCities = document.getElementById('popular-cities');
        const fullCities = document.getElementById('full-cities');
        let isExpanded = false;

        // Open Modal
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.add('active');
            document.documentElement.classList.add('is-hidden');
        });

        // Close Modal
        function closeModal() {
            modal.classList.remove('active');
            document.documentElement.classList.remove('is-hidden');
            isExpanded = false;
            toggleBtn.textContent = 'Показать полный список городов';
            toggleBtn.classList.remove('collapsed');
            popularCities.style.display = 'block';
            fullCities.style.display = 'none';
        }

        closeBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);

        // Keyboard close (Escape key)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });

        // Toggle Cities List
        toggleBtn.addEventListener('click', function() {
            isExpanded = !isExpanded;
            if (isExpanded) {
                popularCities.style.display = 'none';
                fullCities.style.display = 'block';
                toggleBtn.textContent = 'Свернуть список';
                toggleBtn.classList.add('collapsed');
            } else {
                popularCities.style.display = 'block';
                fullCities.style.display = 'none';
                toggleBtn.textContent = 'Показать полный список городов';
                toggleBtn.classList.remove('collapsed');
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
    });
</script>

    