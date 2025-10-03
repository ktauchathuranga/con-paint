<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>con-paint | GitHub Contribution Drawer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0d1117 0%, #21262d 100%);
            color: #f0f6fc;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 700;
            margin-bottom: 2rem;
            background: linear-gradient(45deg, #58a6ff, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .controls {
            background: rgba(33, 38, 45, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid #30363d;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #8b949e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group select,
        .form-group input {
            background: rgba(13, 17, 23, 0.8);
            border: 2px solid #30363d;
            border-radius: 12px;
            padding: 12px 16px;
            color: #f0f6fc;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #58a6ff;
            box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.1);
        }

        .local-timezone-btn {
            background: #444c56;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            color: #f0f6fc;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .local-timezone-btn:hover {
            background: #606770;
        }

        .generate-btn {
            background: linear-gradient(45deg, #238636, #2ea043);
            border: none;
            border-radius: 12px;
            padding: 14px 28px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(35, 134, 54, 0.3);
            justify-self: center;
            grid-column: 1 / -1;
        }

        .generate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(35, 134, 54, 0.4);
        }

        .generate-btn:active {
            transform: translateY(0);
        }

        .graph-container {
            background: rgba(33, 38, 45, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid #30363d;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            overflow-x: auto;
        }

        .graph-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .graph-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #f0f6fc;
        }

        .legend {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #8b949e;
        }

        .legend-item {
            width: 10px;
            height: 10px;
            border-radius: 2px;
        }

        .legend .empty {
            background-color: #21262d;
        }

        .legend .selected {
            background-color: #39d353;
        }

        .contribution-wrapper {
            position: relative;
            min-width: 100%;
            overflow-x: auto;
        }

        .contribution-graph {
            border-spacing: 3px;
            border-collapse: separate;
            margin: 0 auto;
            min-width: 800px;
        }

        .month-labels {
            height: 30px;
        }

        .month-label {
            font-size: 0.75rem;
            color: #8b949e;
            text-align: center;
            font-weight: 500;
            vertical-align: bottom;
            padding-bottom: 5px;
        }

        .day-row {
            height: 15px;
        }

        .day-label {
            font-size: 0.75rem;
            color: #8b949e;
            text-align: right;
            padding-right: 8px;
            vertical-align: middle;
            font-weight: 500;
            width: 30px;
        }

        .contribution-graph td.day {
            width: 11px;
            height: 11px;
            background-color: #21262d;
            border-radius: 2px;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
        }

        .contribution-graph td.day:hover {
            stroke: #58a6ff;
            stroke-width: 2px;
            transform: scale(1.2);
            z-index: 10;
        }

        .contribution-graph td.empty {
            background-color: transparent;
            cursor: default;
            width: 11px;
            height: 11px;
        }

        .contribution-graph td.selected {
            background-color: #39d353;
            box-shadow: 0 0 8px rgba(57, 211, 83, 0.5);
        }

        .contribution-graph td.selected:hover {
            background-color: #2ea043;
        }

        .info-panel {
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(13, 17, 23, 0.5);
            border-radius: 12px;
            border: 1px solid #30363d;
        }

        .selected-count {
            font-size: 0.9rem;
            color: #8b949e;
            text-align: center;
        }

        .selected-count span {
            color: #58a6ff;
            font-weight: 600;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .controls {
                padding: 1.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .graph-container {
                padding: 1rem;
            }

            .contribution-wrapper {
                margin: 0 -1rem;
                padding: 0 1rem;
            }

            .graph-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .day-label {
                font-size: 0.7rem;
                padding-right: 4px;
                width: 25px;
            }

            .month-label {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            .contribution-graph td.day,
            .contribution-graph td.empty {
                width: 9px;
                height: 9px;
            }

            .contribution-graph {
                border-spacing: 2px;
                min-width: 700px;
            }
        }

        /* Scrollbar styling */
        .contribution-wrapper::-webkit-scrollbar {
            height: 6px;
        }

        .contribution-wrapper::-webkit-scrollbar-track {
            background: rgba(33, 38, 45, 0.5);
            border-radius: 3px;
        }

        .contribution-wrapper::-webkit-scrollbar-thumb {
            background: #58a6ff;
            border-radius: 3px;
        }

        .contribution-wrapper::-webkit-scrollbar-thumb:hover {
            background: #79c0ff;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>con-paint | Draw Your GitHub Contribution Graph</h1>

        <div class="controls">
            <div class="form-grid">
                <div class="form-group">
                    <label for="username">GitHub Username</label>
                    <input type="text" id="username" placeholder="Enter your GitHub username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" placeholder="Enter your GitHub email" required>
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="number" id="year" value="2023" min="2000" max="2100">
                </div>
                <div class="form-group">
                    <label for="commits">Commits per Day</label>
                    <input type="number" id="commits" value="1" min="1" max="10">
                </div>
                <div class="form-group">
                    <label for="timezone">Timezone</label>
                    <select id="timezone" name="timezone">
                        <option value="UTC">UTC</option>
                        <option value="Asia/Kolkata">Asia/Kolkata (+05:30)</option>
                        <option value="America/New_York">America/New_York (-05:00)</option>
                        <option value="America/Los_Angeles">America/Los_Angeles (-08:00)</option>
                        <option value="Europe/London">Europe/London (UTC)</option>
                        <option value="Europe/Paris">Europe/Paris (+01:00)</option>
                        <option value="Asia/Tokyo">Asia/Tokyo (+09:00)</option>
                        <option value="Australia/Sydney">Australia/Sydney (+10:00)</option>
                        <option value="Etc/GMT+1">Etc/GMT+1 (-01:00)</option>
                        <option value="Etc/GMT+2">Etc/GMT+2 (-02:00)</option>
                        <option value="Etc/GMT+3">Etc/GMT+3 (-03:00)</option>
                        <option value="Etc/GMT+4">Etc/GMT+4 (-04:00)</option>
                        <option value="Etc/GMT+5">Etc/GMT+5 (-05:00)</option>
                        <option value="Etc/GMT+6">Etc/GMT+6 (-06:00)</option>
                        <option value="Etc/GMT+7">Etc/GMT+7 (-07:00)</option>
                        <option value="Etc/GMT+8">Etc/GMT+8 (-08:00)</option>
                        <option value="Etc/GMT+9">Etc/GMT+9 (-09:00)</option>
                        <option value="Etc/GMT+10">Etc/GMT+10 (-10:00)</option>
                        <option value="Etc/GMT+11">Etc/GMT+11 (-11:00)</option>
                        <option value="Etc/GMT+12">Etc/GMT+12 (-12:00)</option>
                        <option value="Etc/GMT-1">Etc/GMT-1 (+01:00)</option>
                        <option value="Etc/GMT-2">Etc/GMT-2 (+02:00)</option>
                        <option value="Etc/GMT-3">Etc/GMT-3 (+03:00)</option>
                        <option value="Etc/GMT-4">Etc/GMT-4 (+04:00)</option>
                        <option value="Etc/GMT-5">Etc/GMT-5 (+05:00)</option>
                        <option value="Etc/GMT-6">Etc/GMT-6 (+06:00)</option>
                        <option value="Etc/GMT-7">Etc/GMT-7 (+07:00)</option>
                        <option value="Etc/GMT-8">Etc/GMT-8 (+08:00)</option>
                        <option value="Etc/GMT-9">Etc/GMT-9 (+09:00)</option>
                        <option value="Etc/GMT-10">Etc/GMT-10 (+10:00)</option>
                        <option value="Etc/GMT-11">Etc/GMT-11 (+11:00)</option>
                        <option value="Etc/GMT-12">Etc/GMT-12 (+12:00)</option>
                    </select>
                    <button type="button" class="local-timezone-btn" id="local-timezone-btn">Use Local Timezone</button>
                </div>
                <button class="generate-btn" id="generate-btn">Generate & Download Repo</button>
            </div>
        </div>

        <div class="graph-container">
            <div class="graph-header">
                <div class="graph-title">Contribution Activity</div>
                <div class="legend">
                    <span>Less</span>
                    <div class="legend-item empty"></div>
                    <div class="legend-item selected"></div>
                    <span>More</span>
                </div>
            </div>

            <div class="contribution-wrapper">
                <div id="grid-container"></div>
            </div>

            <div class="info-panel">
                <div class="selected-count" id="selected-count">
                    <span>0</span> days selected
                </div>
            </div>
        </div>
    </div>

    <form id="generate-form" action="generate.php" method="post" style="display: none;">
        <input type="hidden" name="dates" id="dates">
        <input type="hidden" name="year" id="form-year">
        <input type="hidden" name="commits" id="form-commits">
        <input type="hidden" name="username" id="form-username">
        <input type="hidden" name="email" id="form-email">
        <input type="hidden" name="timezone" id="form-timezone">
    </form>

    <script>
        let isDrawing = false;
        let selectedDates = new Set();

        function updateSelectedCount() {
            const count = selectedDates.size;
            document.getElementById('selected-count').innerHTML = `<span>${count}</span> day${count !== 1 ? 's' : ''} selected`;
        }

        function generateGrid(year) {
            const gridContainer = document.getElementById('grid-container');
            gridContainer.innerHTML = '';

            const startDate = new Date(Date.UTC(year, 0, 1));
            const firstWeekday = startDate.getUTCDay();
            const isLeap = (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
            const daysInYear = isLeap ? 366 : 365;
            const weeksInYear = Math.ceil((daysInYear + firstWeekday) / 7);

            const table = document.createElement('table');
            table.className = 'contribution-graph';

            // Create month header row
            const monthRow = document.createElement('tr');
            monthRow.className = 'month-labels';
            monthRow.appendChild(document.createElement('td')); // Empty cell for day labels column

            // Calculate month spans
            const monthSpans = [];
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const weekMonths = [];

            for (let week = 0; week < weeksInYear; week++) {
                const weekStartDate = new Date(Date.UTC(year, 0, 1));
                weekStartDate.setUTCDate(1 + (week * 7) - firstWeekday);

                const daysInWeek = [];
                for (let day = 0; day < 7; day++) {
                    const currentDay = new Date(weekStartDate);
                    currentDay.setUTCDate(weekStartDate.getUTCDate() + day);
                    if (currentDay.getUTCFullYear() === year) {
                        daysInWeek.push(currentDay.getUTCMonth());
                    }
                }
                if (daysInWeek.length > 0) {
                    const monthCounts = {};
                    daysInWeek.forEach(month => {
                        monthCounts[month] = (monthCounts[month] || 0) + 1;
                    });
                    const dominantMonth = Object.keys(monthCounts).reduce((a, b) =>
                        monthCounts[a] > monthCounts[b] ? a : b
                    );
                    weekMonths.push(parseInt(dominantMonth));
                } else {
                    weekMonths.push(-1);
                }
            }

            let currentMonth = weekMonths[0];
            let weekCount = 0;
            for (let i = 0; i < weekMonths.length; i++) {
                if (weekMonths[i] === currentMonth && weekMonths[i] !== -1) {
                    weekCount++;
                } else {
                    if (weekCount > 0 && currentMonth !== -1) {
                        monthSpans.push({
                            month: currentMonth,
                            weeks: weekCount
                        });
                    }
                    currentMonth = weekMonths[i];
                    weekCount = weekMonths[i] !== -1 ? 1 : 0;
                }
            }
            if (weekCount > 0 && currentMonth !== -1) {
                monthSpans.push({
                    month: currentMonth,
                    weeks: weekCount
                });
            }

            monthSpans.forEach(span => {
                const cell = document.createElement('td');
                cell.className = 'month-label';
                cell.colSpan = span.weeks;
                cell.textContent = monthNames[span.month];
                monthRow.appendChild(cell);
            });
            table.appendChild(monthRow);

            // Create day rows
            const dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            const dayRows = [];
            for (let i = 0; i < 7; i++) {
                const row = document.createElement('tr');
                row.className = 'day-row';
                const labelCell = document.createElement('td');
                labelCell.className = 'day-label';
                labelCell.textContent = dayLabels[i];
                row.appendChild(labelCell);
                dayRows.push(row);
                table.appendChild(row);
            }

            // Fill rows with days
            let currentDate = new Date(startDate);
            let dayOfWeek = firstWeekday;
            for (let i = 0; i < firstWeekday; i++) {
                const cell = document.createElement('td');
                cell.className = 'empty';
                dayRows[i].appendChild(cell);
            }

            for (let d = 0; d < daysInYear; d++) {
                const cell = document.createElement('td');
                cell.className = 'day';
                const dateStr = currentDate.toISOString().slice(0, 10);
                cell.dataset.date = dateStr;
                cell.title = formatDate(currentDate);
                dayRows[dayOfWeek].appendChild(cell);
                currentDate.setUTCDate(currentDate.getUTCDate() + 1);
                dayOfWeek = (dayOfWeek + 1) % 7;
            }

            const maxCells = Math.max(...dayRows.map(row => row.childElementCount));
            dayRows.forEach(row => {
                while (row.childElementCount < maxCells) {
                    const filler = document.createElement('td');
                    filler.className = 'empty';
                    row.appendChild(filler);
                }
            });

            gridContainer.appendChild(table);
            selectedDates.clear();
            updateSelectedCount();

            table.addEventListener('mousedown', (e) => {
                if (e.target.classList.contains('day')) {
                    isDrawing = true;
                    toggleSelect(e.target);
                    e.preventDefault();
                }
            });

            table.addEventListener('mousemove', (e) => {
                if (isDrawing && e.target.classList.contains('day')) {
                    toggleSelect(e.target);
                }
            });

            table.addEventListener('touchstart', (e) => {
                if (e.target.classList.contains('day')) {
                    toggleSelect(e.target);
                    e.preventDefault();
                }
            });

            table.addEventListener('touchmove', (e) => {
                e.preventDefault();
                const touch = e.touches[0];
                const element = document.elementFromPoint(touch.clientX, touch.clientY);
                if (element && element.classList.contains('day')) {
                    toggleSelect(element);
                }
            });

            document.addEventListener('mouseup', () => {
                isDrawing = false;
            });

            document.addEventListener('touchend', () => {
                isDrawing = false;
            });
        }

        function toggleSelect(element) {
            const date = element.dataset.date;
            if (element.classList.contains('selected')) {
                element.classList.remove('selected');
                selectedDates.delete(date);
            } else {
                element.classList.add('selected');
                selectedDates.add(date);
            }
            updateSelectedCount();
        }

        function formatDate(date) {
            return date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        // Initial grid
        generateGrid(document.getElementById('year').value);

        // Regenerate on year change
        document.getElementById('year').addEventListener('change', (e) => {
            generateGrid(e.target.value);
        });

        // Use local timezone
        document.getElementById('local-timezone-btn').addEventListener('click', () => {
            const localTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            const timezoneSelect = document.getElementById('timezone');
            let option = Array.from(timezoneSelect.options).find(opt => opt.value === localTimezone);
            if (!option) {
                option = document.createElement('option');
                option.value = localTimezone;
                option.textContent = localTimezone;
                timezoneSelect.appendChild(option);
            }
            timezoneSelect.value = localTimezone;
        });

        // Handle generate button
        document.getElementById('generate-btn').addEventListener('click', () => {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const timezone = document.getElementById('timezone').value;

            if (!username || !email) {
                alert('Please enter both a username and email.');
                return;
            }

            if (selectedDates.size === 0) {
                alert('Please select at least one day on the grid.');
                return;
            }

            document.getElementById('dates').value = JSON.stringify([...selectedDates]);
            document.getElementById('form-year').value = document.getElementById('year').value;
            document.getElementById('form-commits').value = document.getElementById('commits').value;
            document.getElementById('form-username').value = username;
            document.getElementById('form-email').value = email;
            document.getElementById('form-timezone').value = timezone;
            document.getElementById('generate-form').submit();
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.day.selected').forEach(cell => {
                    cell.classList.remove('selected');
                });
                selectedDates.clear();
                updateSelectedCount();
            }
        });
    </script>
</body>

</html>
