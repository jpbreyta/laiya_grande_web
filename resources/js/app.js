import './bootstrap';

// 1. Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import Swal from 'sweetalert2';
window.Swal = Swal;

import '@fortawesome/fontawesome-free/css/all.min.css';

import Chart from 'chart.js/auto';
window.Chart = Chart;

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.interactionPlugin = interactionPlugin;