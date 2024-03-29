import {
  useEntityProp,
  store as coreStore,
} from "@wordpress/core-data";
import { useDispatch } from "@wordpress/data";
import {
  Button,
  CheckboxControl,
  SelectControl,
  Notice,
  TextControl,
} from "@wordpress/components";
import { useState } from '@wordpress/element';

const {
  bootstrap_settings: BOOTSTRAP_SETTINGS,
} = window;
const {
  fields: OPTIONS_KEYS,
  versions: ALLOWED_VERSIONS,
} = BOOTSTRAP_SETTINGS;

export default function App() {
  const [version, setVersion] = useEntityProp("root", "site", OPTIONS_KEYS.version);
  const [enableCSS, setEnableCSS] = useEntityProp("root", "site", OPTIONS_KEYS.enable_css);
  const [enableJS, setEnableJS] = useEntityProp("root", "site", OPTIONS_KEYS.enable_js);
  const [cssDependencies, setCSSDependencies] = useEntityProp("root", "site", OPTIONS_KEYS.css_dependencies);
  const [jsDependencies, setJSDependencies] = useEntityProp("root", "site", OPTIONS_KEYS.js_dependencies);

  const [ isSuccessNoticeShown, showSuccessNotice ] = useState(false);

  const { saveEditedEntityRecord } = useDispatch(coreStore);
  const saveOptions = () => {
    return saveEditedEntityRecord("root", "site", undefined, {
      [OPTIONS_KEYS.enable_css]: enableCSS,
      [OPTIONS_KEYS.enable_js]: enableJS,
      [OPTIONS_KEYS.version]: version,
      [OPTIONS_KEYS.css_dependencies]: cssDependencies,
    });
  };

  return (
    <div>
      {isSuccessNoticeShown && (
        <Notice
          status="success"
          onDismiss={() => showSuccessNotice(false)}
          onRemove={() => showSuccessNotice(false)}
        >
          Settings saved!
        </Notice>
      )}
      <h2>Settings</h2>
      <div>
        <SelectControl
          value={version}
          onChange={(newValue) => setVersion(newValue)}
          options={[
            {
              label: "Select an Option",
              value: "",
            },
            ...ALLOWED_VERSIONS.map((version) => {
              return {
                label: version,
                value: version,
              };
            })
          ]}
        />
        <CheckboxControl
          label="Enable CSS"
          checked={enableCSS ? enableJS : false}
          onChange={(newValue) => setEnableCSS(newValue)}
        />
        <TextControl
          label="CSS dependencies"
          help="Add comma-separated style slugs"
          value={cssDependencies
            ? cssDependencies
            : ""
          }
          onChange={(newValue) => setCSSDependencies(newValue)}
        />
        <CheckboxControl
          label="Enable JS"
          checked={enableJS ? enableJS : false}
          onChange={(newValue) => setEnableJS(newValue)}
        />
        <TextControl
          label="JS dependencies"
          help="Add comma-separated script slugs"
          value={jsDependencies
            ? jsDependencies
            : ""
          }
          onChange={(newValue) => setJSDependencies(newValue)}
        />
      </div>
      <Button
        variant="primary"
        onClick={() => saveOptions()
          .then(() => showSuccessNotice(true))
        }
      >Save settings</Button>
    </div>
  );
}
