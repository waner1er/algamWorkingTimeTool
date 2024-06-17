const DAYS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
const MOMENTS = ['m1', 'm2', 'am1', 'am2'];
const INITIAL_REST = parseFloat(document.getElementById('rest').textContent);

const fields = {};
DAYS.forEach(day => {
    MOMENTS.forEach(moment => {
        fields[`${day}-${moment}`] = document.getElementById(`${day}-${moment}`);
    });
});
const totalElement = document.getElementById('resultat');
const restElement = document.getElementById('rest');
const resetFormBtn = document.querySelector('#reset-form');
const storedData = JSON.parse(localStorage.getItem('hoursWorkedData')) || {};


const Utils =
    {
        calculateTimeDifference(start, end) {
            let [startHours, startMinutes] = start.split(':').map(Number);
            let [endHours, endMinutes] = end.split(':').map(Number);

            let diff = (endHours + endMinutes / 60) - (startHours + startMinutes / 60);
            return isNaN(diff) ? 0 : diff;
        },
        formatToHours(decimalHours) {
            const isNegative = decimalHours < 0;
            decimalHours = Math.abs(decimalHours);
            const hours = Math.floor(decimalHours);
            const minutes = Math.round((decimalHours - hours) * 60);

            let result = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
            return isNegative ? `-${result}` : result;
        },
        calculateIntervals() {
            let total = 0;
            let fieldData = {};

            DAYS.forEach(day => {
                const morningInterval = Utils.calculateTimeDifference(fields[`${day}-m1`].value, fields[`${day}-m2`].value);
                const afternoonInterval = Utils.calculateTimeDifference(fields[`${day}-am1`].value, fields[`${day}-am2`].value);

                fieldData[`${day}-m1`] = fields[`${day}-m1`].value;
                fieldData[`${day}-m2`] = fields[`${day}-m2`].value;
                fieldData[`${day}-am1`] = fields[`${day}-am1`].value;
                fieldData[`${day}-am2`] = fields[`${day}-am2`].value;

                total += morningInterval + afternoonInterval;
            });

            totalElement.textContent = Utils.formatToHours(total);

            let rest = INITIAL_REST - total;
            restElement.textContent = Utils.formatToHours(rest);

            localStorage.setItem('hoursWorkedData', JSON.stringify(fieldData));
        },
        exportToCSV() {
            let csv = 'Jour,Matin 1,Matin 2, Après-midi 1, Après-midi 2\n'; // header row
            const storedData = JSON.parse(localStorage.getItem('hoursWorkedData'));

            DAYS.forEach(day => {
                csv += `${day},`;
                MOMENTS.forEach(moment => {
                    csv += `${storedData[`${day}-${moment}`]},`;
                });
                csv = csv.slice(0, -1);  // remove trailing comma
                csv += '\n';
            });

            const blob = new Blob([csv], {type: 'text/csv'});
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'data.csv';

            link.click();
        },
        exportToExcel() {
            const storedData = JSON.parse(localStorage.getItem('hoursWorkedData'));
            const total = formatToHours(parseFloat(totalElement.textContent));
            let data = [["Jour", "Matin 1", "Matin 2", "Après-midi 1", "Après-midi 2"]];

            DAYS.forEach(day => {
                let dayData = [day];
                MOMENTS.forEach(moment => {
                    dayData.push(storedData[`${day}-${moment}`]);
                });
                data.push(dayData);
            });

            data.push(["Total", "", "", "", total]);

            let ws = XLSX.utils.aoa_to_sheet(data);

            let wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

            XLSX.writeFile(wb, "data.xlsx");
        }

    }


Object.keys(storedData).forEach(id => {
    if (fields[id]) {
        fields[id].value = storedData[id];
    }
});

Utils.calculateIntervals();


Object.values(fields).forEach(field => {
    field.addEventListener('change', Utils.calculateIntervals);
});

document.getElementById('exportBtn').addEventListener('click', Utils.exportToExcel);

resetFormBtn.addEventListener('click', (e) => {
    e.preventDefault();

    localStorage.removeItem('hoursWorkedData');
    Object.values(fields).forEach(field => {
        field.value = '';
    });

})

setInterval(function () {
    let date = new Date();
    let options = {
        year: "numeric", month: "numeric",
        day: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit"
    };
    document.querySelector('.actual-date').innerText = date.toLocaleDateString('fr-FR', options);
}, 1000);