import './bootstrap';
import Alpine from 'alpinejs';
import { initNotificationCenter } from './notifications';

window.Alpine = Alpine;
initNotificationCenter();
Alpine.start();
