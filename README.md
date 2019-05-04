# !!!  Attention: Alpha version  !!!

## Install
```
npm install jquery
npm install bootstrap
npm install datatables.net-dt
```

Attach files to the topic:

JS:
```js
// DataTables plugin
import 'web_path/plugins/wbry/objmsg/assets/js/jquery.dataTables.min';
import 'web_path/plugins/wbry/objmsg/assets/js/user-msg-list';

// Bootstrap plugin (if bootstrap style is selected)
import 'web_path/plugins/wbry/objmsg/assets/js/dataTables.bootstrap4.min';
```

SASS:
```sass
@import "web_path/plugins/wbry/objmsg/assets/scss/_user-msg-list.scss";

// Basic style
@import "web_path/plugins/wbry/objmsg/assets/css/jquery.dataTables.min.css";

// OR
// Bootstrap style
@import "web_path/plugins/wbry/objmsg/assets/scss/_bootstrap4-min.scss";
```

```web_path``` path to the root folder "plugins"