import { MDCTemporaryDrawer } from "@material/drawer/temporary/index";

let drawer = new MDCTemporaryDrawer(document.querySelector('.mdc-drawer--temporary'));
document.getElementById('aside_menu').addEventListener('click', () => drawer.open = true);
