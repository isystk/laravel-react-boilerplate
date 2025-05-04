import BasicLayout from '@/components/templates/BasicLayout';
import Carousel from '@/components/atoms/Carousel';
import StockItems from '@/components/organisms/StockItems';

const Top = () => {
  return (
    <BasicLayout title="TOP">
      <Carousel
        images={[
          { src: '/assets/front/image/banner_01.jpg', alt: 'Slide 1' },
          { src: '/assets/front/image/banner_02.jpg', alt: 'Slide 2' },
          { src: '/assets/front/image/banner_01.jpg', alt: 'Slide 3' },
          { src: '/assets/front/image/banner_02.jpg', alt: 'Slide 4' },
        ]}
        autoPlay={true}
        autoPlayInterval={5000}
      />
      <div className="mt-5 md:mt-10">
        <StockItems />
      </div>
    </BasicLayout>
  );
};

export default Top;
