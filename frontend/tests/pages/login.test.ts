import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount, flushPromises } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import LoginPage from '~/pages/login.vue'
import FormInput from '~/components/FormInput.vue'

function mountLogin() {
  return mount(LoginPage, {
    global: {
      stubs: {
        NuxtLayout: { template: '<div><slot /></div>' },
      },
      components: { FormInput },
      plugins: [createPinia()],
    },
  })
}

describe('Login Page', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
  })

  it('renders login form with email and password fields', () => {
    const wrapper = mountLogin()

    expect(wrapper.text()).toContain('ApostaTudo')
    expect(wrapper.text()).toContain('E-mail')
    expect(wrapper.text()).toContain('Senha')
    expect(wrapper.find('button[type="submit"]').text()).toBe('Entrar')
  })

  it('shows required asterisks on both fields', () => {
    const wrapper = mountLogin()

    const labels = wrapper.findAll('label')
    const emailLabel = labels.find(l => l.text().includes('E-mail'))
    const senhaLabel = labels.find(l => l.text().includes('Senha'))

    expect(emailLabel?.text()).toContain('*')
    expect(senhaLabel?.text()).toContain('*')
  })

  it('shows email required error when submitting empty form', async () => {
    const wrapper = mountLogin()

    await wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(wrapper.text()).toContain('O e-mail e obrigatorio.')
    expect(wrapper.text()).toContain('A senha e obrigatoria.')
  })

  it('shows invalid email error for malformed email', async () => {
    const wrapper = mountLogin()

    const inputs = wrapper.findAll('input')
    await inputs[0].setValue('invalidemail')
    await inputs[1].setValue('password123')
    await wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(wrapper.text()).toContain('Informe um e-mail valido.')
  })

  it('does not show email error for valid email format', async () => {
    const wrapper = mountLogin()

    const inputs = wrapper.findAll('input')
    await inputs[0].setValue('user@email.com')
    await inputs[1].setValue('password123')
    await wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(wrapper.text()).not.toContain('O e-mail e obrigatorio.')
    expect(wrapper.text()).not.toContain('Informe um e-mail valido.')
  })

  it('shows password error when only password is empty', async () => {
    const wrapper = mountLogin()

    const inputs = wrapper.findAll('input')
    await inputs[0].setValue('user@email.com')
    await wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(wrapper.text()).not.toContain('O e-mail e obrigatorio.')
    expect(wrapper.text()).toContain('A senha e obrigatoria.')
  })

  it('applies red border on fields with errors', async () => {
    const wrapper = mountLogin()

    await wrapper.find('form').trigger('submit')
    await flushPromises()

    const inputs = wrapper.findAll('input')
    expect(inputs[0].classes()).toContain('border-red-500')
    expect(inputs[1].classes()).toContain('border-red-500')
  })

  it('clears email error when user types', async () => {
    const wrapper = mountLogin()

    await wrapper.find('form').trigger('submit')
    await flushPromises()
    expect(wrapper.text()).toContain('O e-mail e obrigatorio.')

    const inputs = wrapper.findAll('input')
    await inputs[0].setValue('a')
    await flushPromises()

    expect(wrapper.text()).not.toContain('O e-mail e obrigatorio.')
  })

  it('clears password error when user types', async () => {
    const wrapper = mountLogin()

    await wrapper.find('form').trigger('submit')
    await flushPromises()
    expect(wrapper.text()).toContain('A senha e obrigatoria.')

    const inputs = wrapper.findAll('input')
    await inputs[1].setValue('a')
    await flushPromises()

    expect(wrapper.text()).not.toContain('A senha e obrigatoria.')
  })

  it('has novalidate attribute on form', () => {
    const wrapper = mountLogin()

    expect(wrapper.find('form').attributes('novalidate')).toBeDefined()
  })

  it('renders email input with correct type', () => {
    const wrapper = mountLogin()

    const inputs = wrapper.findAll('input')
    expect(inputs[0].attributes('type')).toBe('email')
  })

  it('renders password input with correct type', () => {
    const wrapper = mountLogin()

    const inputs = wrapper.findAll('input')
    expect(inputs[1].attributes('type')).toBe('password')
  })

  it('shows placeholders on inputs', () => {
    const wrapper = mountLogin()

    const inputs = wrapper.findAll('input')
    expect(inputs[0].attributes('placeholder')).toBe('seu@email.com')
    expect(inputs[1].attributes('placeholder')).toContain('••••••••')
  })
})
