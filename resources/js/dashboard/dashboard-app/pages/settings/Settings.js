import React, { useState, useEffect } from 'react';
import { Layout, Form, Input, Button, Select, Space, message, Upload, InputNumber, Checkbox, Switch } from 'antd';
import { UploadOutlined } from '@ant-design/icons';
import { __ } from '@wordpress/i18n';
import postData from '@helpers/postData';

message.config({
  top: '90vh',
});

const { Sider, Content } = Layout;
const { TextArea } = Input;

export default function Settings() {
  const [fields, setFields] = useState([]);
  const [loading, setLoading] = useState(true);
  const [form] = Form.useForm();
  const [activeTab, setActiveTab] = useState('google');

  useEffect(() => {
    setLoading(true);
    postData('login-me-now/admin/settings/fields')
      .then((data) => {
        let fields = data.fields;
        setFields(fields);
        const formData = fields.reduce((acc, field) => {
          acc[field.key] = field.previous_data ?? (field.type === 'checkbox' || field.type === 'switch' ? false : '');
          return acc;
        }, {});
        form.setFieldsValue(formData);
      })
      .catch((error) => {
        message.error(__('Failed to load settings fields.', 'login-me-now'));
        console.error(error);
      })
      .finally(() => {
        setLoading(false);
      });
  }, [form]);

  const tabs = [
    { key: 'wp-native-login', label: __('/wp-admin', 'login-me-now'), section: 'general' },
    { key: 'branden-login', label: __('Branded Login', 'login-me-now'), section: 'general', is_upcoming: true },
    { key: 'license', label: __('License', 'login-me-now'), section: 'general' },
    
    { key: 'google', label: __('Google', 'login-me-now'), section: 'login-providers' },
    { key: 'facebook', label: __('Facebook', 'login-me-now'), section: 'login-providers' },
    { key: 'email-magic-link', label: __('Email Magic Link', 'login-me-now'), section: 'login-providers' },
    { key: 'phone-otp', label: __('Phone OTP', 'login-me-now'), section: 'login-providers', is_upcoming: true },
    // { key: 'twitter-x', label: __('X(Twitter)', 'login-me-now'), section: 'login-providers', is_upcoming: true },
    
    
    { key: 'directorist', label: __('Directorist', 'login-me-now'), section: 'integrations' },
    { key: 'woocommerce', label: __('WooCommerce', 'login-me-now'), section: 'integrations' },
    { key: 'fluent-support', label: __('Fluent Support', 'login-me-now'), section: 'integrations'  },
    { key: 'easy-digital-downloads', label: __('Easy Digital Downloads', 'login-me-now'), section: 'integrations' },
    { key: 'surcease', label: __('SureCart', 'login-me-now'), section: 'integrations', is_upcoming: true },
    
    { key: 'activity-logs', label: __('Activity Logs', 'login-me-now'), section: 'more' },
    { key: 'delegate-access', label: __('Delegate Access', 'login-me-now'), section: 'more' },
    { key: 'custom-support', label: __('Customer Support', 'login-me-now'), section: 'more', is_upcoming: true },
    { key: 'security', label: __('Security', 'login-me-now'), section: 'more', is_upcoming: true },
    { key: 'enterprise', label: __('Enterprise', 'login-me-now'), section: 'more', is_upcoming: true },
    // { key: 'custom-request', label: __('Custom Request', 'login-me-now'), section: 'more', is_upcoming: true  },
    // { key: 'enterprise-features', label: __('Enterprise Features', 'login-me-now'), section: 'more', is_upcoming: true },
  ];

  const sections = [
    { key: 'general', label: __('General', 'login-me-now') },
    { key: 'login-providers', label: __('Login Providers', 'login-me-now') },
    { key: 'integrations', label: __('Integrations', 'login-me-now') },
    { key: 'more', label: __('More', 'login-me-now') },
  ]

  const [forceUpdate, setForceUpdate] = useState(false);

  
  const renderField = (field) => {
    if (field.tab !== activeTab) return null;
  
        // Check if_has (all fields must be truthy)
    if (field.if_has && Array.isArray(field.if_has)) {
      const hasAll = field.if_has.every((requiredField) => {
        const value = form.getFieldValue(requiredField);
        return !!value;
      });

      if (!hasAll) {
        return null;
      }
    }

    // Check if_selected (all fields must match specific value)
    if (field.if_selected && typeof field.if_selected === 'object') {
      const allMatch = Object.entries(field.if_selected).every(([key, expectedValue]) => {
        const value = form.getFieldValue(key);
        return value === expectedValue;
      });

      if (!allMatch) {
        return null;
      }
    }

    const commonProps = {
      name: field.key,
      rules: [
        { required: field.required, message: `${field.title} is required.` },
        field.type === 'email' && { type: 'email', message: __('Invalid email format.', 'login-me-now') },
      ].filter(Boolean),
    };
  
    switch (field.type) {
      case 'text':
      case 'email':
        return (
          <div className='single-field-item'>
            <h3 className="form-field-item-heading text-[18px] text-[#666666] tablet:w-full font-medium"dangerouslySetInnerHTML={{ __html: field.title }}></h3>
            <p className="text-sm mb-2 text-gray-500"dangerouslySetInnerHTML={{ __html: field.description }}></p>

            <Form.Item key={field.key} {...commonProps} className={field.class}>
              <Input disabled={field.is_pro} placeholder={field.placeholder} className="border rounded-lg px-3 py-2 block h-[50px] !p-3 !border-slate-200" />
            </Form.Item>
          </div>
        );
      case 'textarea':
        return (
          <div className='single-field-item'>
             <h3 className="form-field-item-heading text-[18px] text-[#666666] tablet:w-full font-medium"dangerouslySetInnerHTML={{ __html: field.title }}></h3>
             <p className="text-sm mb-2 text-gray-500"dangerouslySetInnerHTML={{ __html: field.description }}></p>
            <Form.Item key={field.key} {...commonProps} tooltip={field.tooltip} className={field.class}>
              <TextArea disabled={field.is_pro} placeholder={field.placeholder} rows={4} className="block h-[50px] !p-3 !border-slate-200" />
            </Form.Item>
          </div>
        );
      case 'color':
        return (
          <div className='single-field-item'>
            <h3 className="form-field-item-heading text-[18px] text-[#666666] tablet:w-full font-medium"dangerouslySetInnerHTML={{ __html: field.title }}></h3>
            <p className="text-sm mb-2 text-gray-500"dangerouslySetInnerHTML={{ __html: field.description }}></p>

            <Form.Item key={field.key} {...commonProps} tooltip={field.tooltip} className={field.class}>
              <Input disabled={field.is_pro} type="color" className="w-16 h-10 border rounded-lg" />
            </Form.Item>
          </div>
        );
      case 'file':
        return (
            <div className='single-field-item'>
              <h3 className="form-field-item-heading text-[18px] text-[#666666] tablet:w-full font-medium"dangerouslySetInnerHTML={{ __html: field.title }}></h3>
              <p className="text-sm mb-2 text-gray-500"dangerouslySetInnerHTML={{ __html: field.description }}></p>
              <Form.Item key={field.key} {...commonProps} tooltip={field.tooltip} className={field.class}>
                <Upload disabled={field.is_pro} beforeUpload={() => false} maxCount={1}>
                  <Button icon={<UploadOutlined />} className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    {__('Upload File', 'login-me-now')}
                  </Button>
                </Upload>
              </Form.Item>
          </div>
        );
      case 'number':
        return (
          <div className='single-field-item'>
          <h3 className="form-field-item-heading text-[18px] text-[#666666] tablet:w-full font-medium"dangerouslySetInnerHTML={{ __html: field.title }}></h3>
          <p className="text-sm mb-2 text-gray-500"dangerouslySetInnerHTML={{ __html: field.description }}></p>
          <Form.Item disabled={field.is_pro} key={field.key} {...commonProps} tooltip={field.tooltip} className={field.class}>
            <InputNumber placeholder={field.placeholder} className="w-full border rounded-lg px-3 py-2" />
          </Form.Item>
          </div>
        );
      case 'checkbox':
        return (
          <Form.Item key={field.key} name={field.key} valuePropName="checked" className="flex items-center space-x-2" tooltip={field.tooltip}>
            <div>
              <Checkbox disabled={field.is_pro}>{field.description}</Checkbox>
            </div>
          </Form.Item>
        );
      case 'switch':
        return (
          <div className='single-field-item custom-checkbox-class flex items-center space-x-2'>

            <div>
              <h3 className="form-field-item-heading text-[18px] text-[#666666] tablet:w-full font-medium"dangerouslySetInnerHTML={{ __html: field.title }}></h3>
              <p className="text-sm mb-2 text-gray-500"dangerouslySetInnerHTML={{ __html: field.description }}></p>
            </div>

            <Form.Item
              key={field.key}
              name={field.key}
              valuePropName="checked"
              initialValue={false}  // Ensure there's an initial value
              rules={[
                { required: false, message: `${field.title} is required.` },
              ]}
              tooltip={field.tooltip}
              className={field.class}
            >
            <Switch disabled={field.is_pro} />
            </Form.Item>
          </div>
        ); 
      case 'select':
        return (
          <div className="single-field-item">
            <h3 className="form-field-item-heading  text-[18px] text-[#666666] tablet:w-full font-medium">{field.title}</h3>
            <p className="text-sm mb-2 text-gray-500">{field.description}</p>
            
            <Form.Item key={field.key} {...commonProps} tooltip={field.tooltip}>
              <Select disabled={field.is_pro} placeholder={field.placeholder} className="w-full">
                {field.options?.map((option) => (
                  <Select.Option key={option.value} value={option.value}>
                    {option.label}
                  </Select.Option>
                ))}
              </Select>
            </Form.Item>
          </div>
        );

      case 'multi-select':
          return (
            <div className="single-field-item">
              <h3 className="form-field-item-heading  text-[18px] text-[#666666] tablet:w-full font-medium">{field.title}</h3>
              <p className="text-sm mb-2 text-gray-500">{field.description}</p>

              <Form.Item key={field.key} {...commonProps} tooltip={field.tooltip}>
                <Select
                  disabled={field.is_pro}
                  mode="multiple"
                  placeholder={field.placeholder || __('Select multiple options', 'login-me-now')}
                  className="w-full"
                  options={field.options?.map(option => ({
                    label: option.label,
                    value: option.value,
                  }))}
                />
              </Form.Item>
            </div>
          );
      
      case 'separator':
        return (
          <div className="login-me-now-separator"></div>
        );
      default:
        return null;
    }
  };
  

  const handleSave = (values) => {
    setLoading(true); // Show loading state
    postData('login-me-now/admin/settings/save', values)
      .then((response) => {
        if (response.success) {
          message.success(__('Settings saved successfully!', 'login-me-now'));
        } else {
          throw new Error(response.message || __('Failed to save settings.', 'login-me-now'));
        }
      })
      .catch((error) => {
        message.error(error.message || __('Failed to save settings.', 'login-me-now'));
        console.error('Save Settings Error:', error);
      })
      .finally(() => {
        setLoading(false); // Remove loading state
      });
  };
  

  return (
    <div className="max-w-3xl mx-auto px-6 lg:max-w-screen-2xl">
      <div className="mx-auto mt-10 mb-8 font-semibold text-2xl">
        Settings
      </div>

      <Layout className="mx-auto my-[2.43rem] bg-white rounded-md shadow overflow-hidden min-h-[36rem]">
        <Sider width={350} className="bg-black-100 p-6">

          <ul className="space-y-4">
            {sections.map((section) => {
              const sectionTabs = tabs.filter((tab) => tab.section === section.key);

              if (sectionTabs.length === 0) {
                return null; // Skip rendering if no tabs under this section
              }

              return (
                <li key={section.key}>
                  <div className="text-gray-500 uppercase text-xs font-semibold mb-2">{section.label}</div>
                  <ul className="space-y-2">
                  {sectionTabs.map((tab) => (
                    <li
                      key={tab.key}
                      className={`p-2 rounded-lg cursor-pointer flex items-center justify-between hover:bg-blue-100 hover:text-blue-700 text-white ${
                        tab.is_upcoming ? 'opacity-50 cursor-not-allowed' : (activeTab === tab.key ? 'bg-blue-500' : 'hover:bg-gray-200 text-black')
                      }`}
                      onClick={() => {
                        if (!tab.is_upcoming) {
                          setActiveTab(tab.key);
                        }
                      }}
                      title={tab.is_upcoming ? 'Coming Soon' : ''}
                    >
                      <span>{tab.label}</span>
                      {tab.is_upcoming && (
                        <span className="bg-yellow-300 text-yellow-900 text-xs font-semibold px-2 py-0.5 rounded-md ml-2">
                          Upcoming
                        </span>
                      )}
                    </li>
                  ))}

                  </ul>
                </li>
              );
            })}
          </ul>

        </Sider>

        <Content className="p-10 w-full">
          {activeTab && (
            
            <h2 className="text-2xl font-bold mb-6">
              {tabs.find(tab => tab.key === activeTab)?.label}
            </h2>
          )}
          <Form 
          form={form} 
          layout="vertical" 
          onFinish={handleSave} 
          disabled={loading}
          onValuesChange={() => {
            setForceUpdate(x => !x);
          }}
          >
            <div className="grid grid-cols-1 gap-0">
              {fields.map((field) => renderField(field))}
            </div>
            <Form.Item className="mt-6">
              <Space>
                <Button type="primary" htmlType="submit" className="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg disabled:opacity-50">
                  {__('Save Settings', 'login-me-now')}
                </Button>
              </Space>
            </Form.Item>
          </Form>
        </Content>

      </Layout>
    </div>
  );
}