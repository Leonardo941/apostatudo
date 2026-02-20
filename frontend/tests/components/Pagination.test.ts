import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import Pagination from '~/components/Pagination.vue'

describe('Pagination', () => {
  it('renders per-page selector with default options', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 1, lastPage: 5, total: 50 },
    })

    const options = wrapper.findAll('select option')
    expect(options).toHaveLength(5)
    expect(options.map(o => o.text())).toEqual(['5', '10', '15', '25', '50'])
  })

  it('renders per-page selector with custom options', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 1, lastPage: 5, total: 50, perPageOptions: [10, 20, 30] },
    })

    const options = wrapper.findAll('select option')
    expect(options).toHaveLength(3)
    expect(options.map(o => o.text())).toEqual(['10', '20', '30'])
  })

  it('emits per-page-change when select changes', async () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 1, lastPage: 5, total: 50 },
    })

    await wrapper.find('select').setValue('25')

    expect(wrapper.emitted('per-page-change')).toBeTruthy()
    expect(wrapper.emitted('per-page-change')![0]).toEqual([25])
  })

  it('displays total records count', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 1, lastPage: 5, total: 123 },
    })

    expect(wrapper.text()).toContain('123 registros')
  })

  it('renders page buttons correctly', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 1, lastPage: 5, total: 50 },
    })

    const nav = wrapper.find('nav')
    expect(nav.exists()).toBe(true)
    const buttons = nav.findAll('button')
    // first, prev, 5 pages, next, last = 9 buttons
    expect(buttons).toHaveLength(9)
  })

  it('shows max 5 page buttons', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 5, lastPage: 20, total: 200 },
    })

    const nav = wrapper.find('nav')
    const pageButtons = nav.findAll('button').filter(b => /^\d+$/.test(b.text()))
    expect(pageButtons).toHaveLength(5)
  })

  it('highlights the current page', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 3, lastPage: 5, total: 50 },
    })

    const nav = wrapper.find('nav')
    const currentButton = nav.findAll('button').find(b => b.text() === '3')
    expect(currentButton?.classes()).toContain('bg-violet-600')
  })

  it('emits page-change on page button click', async () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 1, lastPage: 5, total: 50 },
    })

    const nav = wrapper.find('nav')
    const page2Button = nav.findAll('button').find(b => b.text() === '2')
    await page2Button!.trigger('click')

    expect(wrapper.emitted('page-change')).toBeTruthy()
    expect(wrapper.emitted('page-change')![0]).toEqual([2])
  })

  it('hides navigation when only one page', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 1, lastPage: 1, total: 5 },
    })

    expect(wrapper.find('nav').exists()).toBe(false)
    // Per-page selector still renders
    expect(wrapper.find('select').exists()).toBe(true)
  })

  it('disables first and previous buttons on first page', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 1, lastPage: 3, total: 30 },
    })

    const nav = wrapper.find('nav')
    const buttons = nav.findAll('button')
    const firstBtn = buttons.find(b => b.attributes('title') === 'Primeira pagina')
    const prevBtn = buttons.find(b => b.attributes('title') === 'Pagina anterior')

    expect(firstBtn?.attributes('disabled')).toBeDefined()
    expect(prevBtn?.attributes('disabled')).toBeDefined()
  })

  it('disables next and last buttons on last page', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 3, lastPage: 3, total: 30 },
    })

    const nav = wrapper.find('nav')
    const buttons = nav.findAll('button')
    const nextBtn = buttons.find(b => b.attributes('title') === 'Proxima pagina')
    const lastBtn = buttons.find(b => b.attributes('title') === 'Ultima pagina')

    expect(nextBtn?.attributes('disabled')).toBeDefined()
    expect(lastBtn?.attributes('disabled')).toBeDefined()
  })

  it('enables all navigation buttons on middle page', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 2, lastPage: 3, total: 30 },
    })

    const nav = wrapper.find('nav')
    const buttons = nav.findAll('button')
    const firstBtn = buttons.find(b => b.attributes('title') === 'Primeira pagina')
    const prevBtn = buttons.find(b => b.attributes('title') === 'Pagina anterior')
    const nextBtn = buttons.find(b => b.attributes('title') === 'Proxima pagina')
    const lastBtn = buttons.find(b => b.attributes('title') === 'Ultima pagina')

    expect(firstBtn?.attributes('disabled')).toBeUndefined()
    expect(prevBtn?.attributes('disabled')).toBeUndefined()
    expect(nextBtn?.attributes('disabled')).toBeUndefined()
    expect(lastBtn?.attributes('disabled')).toBeUndefined()
  })

  it('emits page-change with 1 when first button clicked', async () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 3, lastPage: 5, total: 50 },
    })

    const firstBtn = wrapper.find('nav').findAll('button').find(b => b.attributes('title') === 'Primeira pagina')
    await firstBtn!.trigger('click')

    expect(wrapper.emitted('page-change')![0]).toEqual([1])
  })

  it('emits page-change with lastPage when last button clicked', async () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 1, lastPage: 5, total: 50 },
    })

    const lastBtn = wrapper.find('nav').findAll('button').find(b => b.attributes('title') === 'Ultima pagina')
    await lastBtn!.trigger('click')

    expect(wrapper.emitted('page-change')![0]).toEqual([5])
  })

  it('shows sliding window of pages centered on current', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 10, lastPage: 20, total: 200 },
    })

    const nav = wrapper.find('nav')
    const pageButtons = nav.findAll('button').filter(b => /^\d+$/.test(b.text()))
    const pages = pageButtons.map(b => Number(b.text()))

    expect(pages).toEqual([8, 9, 10, 11, 12])
  })

  it('adjusts window at end of range', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 19, lastPage: 20, total: 200 },
    })

    const nav = wrapper.find('nav')
    const pageButtons = nav.findAll('button').filter(b => /^\d+$/.test(b.text()))
    const pages = pageButtons.map(b => Number(b.text()))

    expect(pages).toEqual([16, 17, 18, 19, 20])
  })

  it('adjusts window at start of range', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 2, lastPage: 20, total: 200 },
    })

    const nav = wrapper.find('nav')
    const pageButtons = nav.findAll('button').filter(b => /^\d+$/.test(b.text()))
    const pages = pageButtons.map(b => Number(b.text()))

    expect(pages).toEqual([1, 2, 3, 4, 5])
  })

  it('renders itens por pagina label', () => {
    const wrapper = mount(Pagination, {
      props: { currentPage: 1, lastPage: 5, total: 50 },
    })

    expect(wrapper.text()).toContain('Itens por pagina:')
  })
})
