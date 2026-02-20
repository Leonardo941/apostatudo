import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import ConfirmDialog from '~/components/ConfirmDialog.vue'

describe('ConfirmDialog', () => {
  const defaultProps = {
    show: true,
    title: 'Delete Item',
    message: 'Are you sure you want to delete this item?',
  }

  it('renders title and message when shown', () => {
    const wrapper = mount(ConfirmDialog, { props: defaultProps })

    expect(wrapper.text()).toContain('Delete Item')
    expect(wrapper.text()).toContain('Are you sure you want to delete this item?')
  })

  it('emits confirm on confirm button click', async () => {
    const wrapper = mount(ConfirmDialog, { props: defaultProps })

    const buttons = wrapper.findAll('button')
    const confirmButton = buttons.find((b) => b.text() === 'Confirmar')
    await confirmButton!.trigger('click')

    expect(wrapper.emitted('confirm')).toBeTruthy()
  })

  it('emits cancel on cancel button click', async () => {
    const wrapper = mount(ConfirmDialog, { props: defaultProps })

    const buttons = wrapper.findAll('button')
    const cancelButton = buttons.find((b) => b.text() === 'Cancelar')
    await cancelButton!.trigger('click')

    expect(wrapper.emitted('cancel')).toBeTruthy()
  })

  it('is hidden when show is false', () => {
    const wrapper = mount(ConfirmDialog, {
      props: { ...defaultProps, show: false },
    })

    expect(wrapper.html()).toBe('<!--v-if-->')
  })

  it('emits cancel when backdrop is clicked', async () => {
    const wrapper = mount(ConfirmDialog, { props: defaultProps })

    const backdrop = wrapper.find('.fixed.inset-0.bg-black\\/60')
    await backdrop.trigger('click')

    expect(wrapper.emitted('cancel')).toBeTruthy()
  })
})
