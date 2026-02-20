import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import FormInput from '~/components/FormInput.vue'

describe('FormInput', () => {
  it('renders label when provided', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '', label: 'Nome' },
    })

    expect(wrapper.find('label').text()).toContain('Nome')
  })

  it('does not render label when not provided', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '' },
    })

    expect(wrapper.find('label').exists()).toBe(false)
  })

  it('shows asterisk when required', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '', label: 'Nome', required: true },
    })

    expect(wrapper.find('label span').exists()).toBe(true)
    expect(wrapper.find('label span').text()).toBe('*')
  })

  it('does not show asterisk when not required', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '', label: 'Hobby' },
    })

    expect(wrapper.find('label span').exists()).toBe(false)
  })

  it('renders input with correct type', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '', type: 'email' },
    })

    expect(wrapper.find('input').attributes('type')).toBe('email')
  })

  it('defaults to text type', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '' },
    })

    expect(wrapper.find('input').attributes('type')).toBeUndefined()
  })

  it('renders placeholder', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '', placeholder: 'Digite aqui' },
    })

    expect(wrapper.find('input').attributes('placeholder')).toBe('Digite aqui')
  })

  it('sets maxlength attribute', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '', maxlength: 255 },
    })

    expect(wrapper.find('input').attributes('maxlength')).toBe('255')
  })

  it('sets max attribute for date inputs', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '', type: 'date', max: '2025-01-01' },
    })

    expect(wrapper.find('input').attributes('max')).toBe('2025-01-01')
  })

  it('shows character counter when maxlength is set', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: 'abc', maxlength: 255 },
    })

    expect(wrapper.text()).toContain('3/255')
  })

  it('updates character counter based on modelValue', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: 'hello world', maxlength: 255 },
    })

    expect(wrapper.text()).toContain('11/255')
  })

  it('does not show character counter without maxlength', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: 'test' },
    })

    expect(wrapper.text()).not.toContain('/255')
  })

  it('shows error message when error prop is set', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '', error: 'O nome e obrigatorio.' },
    })

    expect(wrapper.text()).toContain('O nome e obrigatorio.')
  })

  it('applies red border class when error is present', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '', error: 'Campo obrigatorio' },
    })

    expect(wrapper.find('input').classes()).toContain('border-red-500')
  })

  it('applies normal border class when no error', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '' },
    })

    expect(wrapper.find('input').classes()).toContain('border-gray-300')
  })

  it('shows error alongside character counter when both present', () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '', maxlength: 255, error: 'Campo obrigatorio' },
    })

    expect(wrapper.text()).toContain('Campo obrigatorio')
    expect(wrapper.text()).toContain('0/255')
  })

  it('emits update:modelValue on input', async () => {
    const wrapper = mount(FormInput, {
      props: { modelValue: '' },
    })

    await wrapper.find('input').setValue('test')

    expect(wrapper.emitted('update:modelValue')).toBeTruthy()
    expect(wrapper.emitted('update:modelValue')![0]).toEqual(['test'])
  })
})
