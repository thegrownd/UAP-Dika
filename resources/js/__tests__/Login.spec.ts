import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Login from '../components/Login.vue';

describe('Login.vue', () => {
  it('renders form fields', () => {
    const wrapper = mount(Login);
    expect(wrapper.text()).toContain('Login');
    expect(wrapper.find('input[type="email"]').exists()).toBe(true);
    expect(wrapper.find('input[type="password"]').exists()).toBe(true);
  });
});

