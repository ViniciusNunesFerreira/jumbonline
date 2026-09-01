import quill from 'quill/dist/quill.js';
import 'quill/dist/quill.snow.css';
import QuillBetterTablePlus from 'quill-better-table-plus';
import 'quill-better-table-plus/dist/quill-better-table-plus.css';

quill.register({ 'modules/better-table-plus': QuillBetterTablePlus }, true);

window.Quill = quill;
