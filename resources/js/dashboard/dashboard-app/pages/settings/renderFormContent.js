import { __ } from '@wordpress/i18n';
import { Form, Input, Select, InputNumber, Checkbox, Switch, Card } from 'antd';

const { TextArea } = Input;
const { Option } = Select;

export default function renderFormContent( selectedMenuKey ) {
	switch (selectedMenuKey) {
		case 'general':
		  return (
			<Card title={__('General Settings', 'login-me-now')} bordered={false}>
			  
			  <Form.Item
				label={__('Site Name', 'login-me-now')}
				name="site_name"
				rules={[{ message: __('Please input Site Name!', 'login-me-now') }]}
			  >
				<Input placeholder={__('Enter your site name', 'login-me-now')} />
			  </Form.Item>
  
			  <Form.Item
				label={__('Site Description', 'login-me-now')}
				name="site_description"
			  >
				<TextArea placeholder={__('Enter your site description', 'login-me-now')} />
			  </Form.Item>
  
			  <Form.Item
				label={__('Default Language', 'login-me-now')}
				name="default_language"
			  >
				<Select placeholder={__('Select language', 'login-me-now')}>
				  <Option value="en">English</Option>
				  <Option value="fr">French</Option>
				  <Option value="es">Spanish</Option>
				  <Option value="de">German</Option>
				</Select>
			  </Form.Item>
  
			</Card>
		  );
  
		case 'appearance':
		  return (
			<Card title={__('Appearance Settings', 'login-me-now')} bordered={false}>
			  <Form.Item
				label={__('Theme Color', 'login-me-now')}
				name="theme_color"
			  >
				<Input type="color" />
			  </Form.Item>
  
			  <Form.Item
				label={__('Custom CSS', 'login-me-now')}
				name="custom_css"
			  >
				<TextArea placeholder={__('Enter custom CSS', 'login-me-now')} rows={4} />
			  </Form.Item>
  
			  <Form.Item
				label={__('Font Size', 'login-me-now')}
				name="font_size"
			  >
				<InputNumber min={10} max={36} />
			  </Form.Item>
			</Card>
		  );
  
		case 'security':
		  return (
			<Card title={__('Security Settings', 'login-me-now')} bordered={false}>
			  <Form.Item
				label={__('Enable 2FA', 'login-me-now')}
				name="enable_2fa"
				valuePropName="checked"
			  >
				<Switch />
			  </Form.Item>
  
			  <Form.Item
				label={__('Password Complexity', 'login-me-now')}
				name="password_complexity"
			  >
				<Checkbox.Group>
				  <Checkbox value="uppercase">{__('Uppercase Letters', 'login-me-now')}</Checkbox>
				  <Checkbox value="numbers">{__('Numbers', 'login-me-now')}</Checkbox>
				  <Checkbox value="symbols">{__('Symbols', 'login-me-now')}</Checkbox>
				</Checkbox.Group>
			  </Form.Item>
			</Card>
		  );
  
		case 'profile':
		  return (
			<Card title={__('Profile Settings', 'login-me-now')} bordered={false}>
			  <Form.Item
				label={__('Username', 'login-me-now')}
				name="username"
			  >
				<Input placeholder={__('Enter your username', 'login-me-now')} />
			  </Form.Item>
  
			  <Form.Item
				label={__('Bio', 'login-me-now')}
				name="bio"
			  >
				<TextArea placeholder={__('Tell us about yourself', 'login-me-now')} rows={4} />
			  </Form.Item>
  
			  <Form.Item
				label={__('Preferred Language', 'login-me-now')}
				name="preferred_language"
			  >
				<Select mode="multiple" placeholder={__('Select your preferred languages', 'login-me-now')}>
				  <Option value="en">English</Option>
				  <Option value="fr">French</Option>
				  <Option value="es">Spanish</Option>
				  <Option value="de">German</Option>
				</Select>
			  </Form.Item>
			</Card>
		  );
  
		case 'license':
		  return (
			<Card title={__('License Settings', 'login-me-now')} bordered={false}>
				<Form.Item
					label={__('License Key', 'login-me-now')}
					name="license_key"
				>
					<Input placeholder={__('Please enter you license key here', 'login-me-now')} />
				</Form.Item>
					<p>{__( "If you don't have a valid license, please purchase one from the ", 'login-me-now' )}  <a target="__blank" href='https://loginmenow.com/pricing/'>official website</a></p>
			</Card>
		);
		case 'tools':
		  return (
			<Card title={__('Tools Settings', 'login-me-now')} bordered={false}>
				<Form.Item
					label={__('License Key', 'login-me-now')}
					name="license_key"
				>
					<Input placeholder={__('Please enter you license key here', 'login-me-now')} />
				</Form.Item>
					<p>{__( "If you don't have a valid license, please purchase one from the ", 'login-me-now' )}  <a target="__blank" href='https://loginmenow.com/pricing/'>official website</a></p>
			</Card>
		);

		default:
		  return null;
	  }
}
