#Решаемая проблема:
Реализовать упрощенный прототип системы сопровождения клиента. Предположим у нас есть отдел по работе с клиентами который принимает телефонные звонки от клиентов с зачастую однотипными проблемами с известным решением. Требуется создать инструмент-помошник для принимающего звонок подсказывающий какую информацию нужно спросить у клиента и какие действия выполнить в зависимости от проблемы.

#Решение:
Необходимо сформировать последовательность вопросов в виде направленного графа в котором каждая нода содержит следующие вещи: 
1. Вопрос, показанный как модальное окно для оператора
2. Каждый вопрос должен предусматривать несколько вариантов ответа, но для простоты сделаем так что выбирать можно только один из них. 
4. В зависимости от выбранного ответа осуществляется переход к следующей ноде и так далее до терминальной ноды. 
5. Дополнительные поля в которые можно вводить информацию, для простоты ограничимся текстовыми полями

__Необходимо__ написать веб интерфейс для такой вот системы сопровождения клиента. 
Как это может выглядеть: оператор заходит на страницу в браузере (авторизацию оставим в стороне), видит первый вопрос:
1) Окно 1 (корневая нода)

Вопрос который необходимо задать:
"По какому поводу звоните?"

Варианты ответа:
а) Звонок по поводу проблемы качества
b) Вопрос по доставке
c) Консультация/Другое

При выборе ответа b) осуществляется переход на следующее окно где:
Вопрос: Номера заказа?
Ответ: ХХХ (дополнительное поле из пункта 5)

При выборе ответа а)
Вопрос: что случилось
Ответ: описание  проблемы (дополнительное поле)

Аналогично при ответе с)

#Требования к реализации:
1. ООП: Поведение каждой ноды должно определяться собственным классом-обработчиком. Т.е. если например требуется совершить действие поиска номера заказа или записи контактных данных или рейтинга - это долно осуществляться методами
класса обработчика конкретного типа ноды.
Общие для всех нод данные и методы (вопросы, переходы) должны
обрабатываться родительским классом.
2. Структура связей-переходов между нодами и определение самих нод
может быть задана заранее в конфигурационном файле (yml, xml, json на выбор) или таблице в базе данных
3. Переходы между вопросами должны быть без перезагрузок страницы
4. Допустимо (и целесообразно) использовать javascript фреймворки и библиотеки такие как jquery, jquery ui и подобные
5. данные по каждому обращению клиента должны записываться в таблицу-лог, значения кастомных текстовых полей пишутся в этот же лог
6. Язык — php
7. База данных - mysql
8. Неплохо показать умение работать с ajax и json
