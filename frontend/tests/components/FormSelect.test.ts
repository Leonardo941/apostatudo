import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import FormSelect from '~/components/FormSelect.vue'

describe('FormSelect', () => {
  const defaultSlots = {
    default: `
      <option value="M">Masculino</option>
      <option value="F">Feminino</option>
      <option value="O">Outro</option>
    `,
  }

  it('renders label when provided', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '', label: 'Sexo' },
      slots: defaultSlots,
    })

    expect(wrapper.find('label').text()).toContain('Sexo')
  })

  it('does not render label when not provided', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '' },
      slots: defaultSlots,
    })

    expect(wrapper.find('label').exists()).toBe(false)
  })

  it('shows asterisk when required', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '', label: 'Nivel', required: true },
      slots: defaultSlots,
    })

    expect(wrapper.find('label span').exists()).toBe(true)
    expect(wrapper.find('label span').text()).toBe('*')
  })

  it('does not show asterisk when not required', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '', label: 'Sexo' },
      slots: defaultSlots,
    })

    expect(wrapper.find('label span').exists()).toBe(false)
  })

  it('renders placeholder option when provided', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '', placeholder: 'Selecione um nivel' },
      slots: defaultSlots,
    })

    const options = wrapper.findAll('option')
    expect(options[0].text()).toBe('Selecione um nivel')
    expect(options[0].attributes('disabled')).toBeDefined()
    expect(options[0].attributes('value')).toBe('')
  })

  it('does not render placeholder option when not provided', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '' },
      slots: defaultSlots,
    })

    const options = wrapper.findAll('option')
    expect(options[0].text()).toBe('Masculino')
  })

  it('renders slot options', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '' },
      slots: defaultSlots,
    })

    const options = wrapper.findAll('option')
    expect(options).toHaveLength(3)
    expect(options[0].text()).toBe('Masculino')
    expect(options[1].text()).toBe('Feminino')
    expect(options[2].text()).toBe('Outro')
  })

  it('shows error message when error prop is set', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '', error: 'Selecione o sexo.' },
      slots: defaultSlots,
    })

    expect(wrapper.text()).toContain('Selecione o sexo.')
  })

  it('applies red border class when error is present', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '', error: 'Campo obrigatorio' },
      slots: defaultSlots,
    })

    expect(wrapper.find('select').classes()).toContain('border-red-500')
  })

  it('applies normal border class when no error', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '' },
      slots: defaultSlots,
    })

    expect(wrapper.find('select').classes()).toContain('border-gray-300')
  })

  it('does not show error message when no error', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '' },
      slots: defaultSlots,
    })

    const errorSpan = wrapper.find('.text-red-500')
    expect(errorSpan.exists()).toBe(false)
  })

  it('has appearance-none class for custom arrow', () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '' },
      slots: defaultSlots,
    })

    expect(wrapper.find('select').classes()).toContain('appearance-none')
  })

  it('emits update:modelValue on change', async () => {
    const wrapper = mount(FormSelect, {
      props: { modelValue: '' },
      slots: defaultSlots,
    })

    await wrapper.find('select').setValue('F')

    expect(wrapper.emitted('update:modelValue')).toBeTruthy()
    expect(wrapper.emitted('update:modelValue')![0]).toEqual(['F'])
  })
})
