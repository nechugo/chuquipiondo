#!/usr/bin/env python3
"""Genera el paquete de contenido demo de nechugo News.

Crea:
- demo-posts.xml  (WXR con 9 entradas de empleos, 800-1000 palabras cada una)
"""
import html

def cuerpo(puesto, empresa, ubicacion, tipo, salario, requisitos, funciones, beneficios, proceso):
    p = []
    p.append(f"<p>La empresa <strong>{empresa}</strong> ha abierto una nueva convocatoria para el puesto de <strong>{puesto}</strong> en {ubicacion}. Esta oportunidad laboral corresponde a un contrato de tipo {tipo}, con una remuneracion que parte de {salario} segun la experiencia y las competencias demostradas por cada candidato durante el proceso de seleccion. A continuacion encontraras toda la informacion necesaria para postular: perfil buscado, funciones principales, beneficios incluidos, etapas del proceso y el paso a paso para enviar tu candidatura sin errores.</p>")
    p.append("<h2>Sobre la empresa</h2>")
    p.append(f"<p>{empresa} es una organizacion consolidada que opera en {ubicacion} y mantiene un compromiso permanente con el desarrollo de su equipo de colaboradores. La empresa invierte en formacion continua, condiciones laborales estables y un ambiente de trabajo basado en el respeto, la colaboracion y el crecimiento profesional medible. Cada ano incorpora nuevos talentos a sus distintas areas operativas y administrativas, por lo que esta convocatoria representa una puerta de entrada real para quienes buscan estabilidad laboral y proyeccion de carrera dentro de una estructura organizacional clara.</p>")
    p.append("<h2>Descripcion del puesto</h2>")
    p.append(f"<p>El rol de {puesto} implica trabajar de manera activa dentro de la operacion diaria de {empresa}, reportando al responsable del area correspondiente. El contrato es {tipo}, con horarios organizados segun las necesidades del servicio y un periodo de adaptacion acompañado por personal con experiencia. Durante los primeros meses, la persona seleccionada recibira acompanamiento cercano, retroalimentacion constante y acceso a los manuales internos de procedimiento para garantizar una integracion rapida y eficiente al equipo de trabajo.</p>")
    p.append("<h2>Perfil del candidato</h2>")
    p.append("<p>Los interesados en esta vacante deberan cumplir con el siguiente perfil general, que combina formacion academica, experiencia practica y competencias personales verificables durante las entrevistas. La empresa valora tanto los conocimientos tecnicos como la actitud frente al trabajo en equipo, la resolucion de problemas bajo presion y la disposicion para aprender procesos nuevos en periodos cortos de adaptacion. No es indispensable cumplir el cien por ciento de los requisitos para postular: aquellos candidatos que se acerquen al perfil y demuestren motivacion tambien seran evaluados.</p>")
    req = "".join(f"<li>{r}</li>" for r in requisitos)
    p.append(f"<ul>{req}</ul>")
    p.append("<h2>Funciones principales</h2>")
    p.append(f"<p>La persona seleccionada sera responsable de las siguientes funciones dentro del area correspondiente. Las tareas se distribuyen segun la operacion diaria y pueden ajustarse conforme a la evolucion del proyecto, siempre dentro del ambito del puesto y con el respaldo del equipo de supervision asignado a cada turno o departamento. El cumplimiento de estandares de calidad, seguridad y puntualidad forma parte transversal de todas las funciones descritas a continuacion.</p>")
    fun = "".join(f"<li>{f}</li>" for f in funciones)
    p.append(f"<ul>{fun}</ul>")
    p.append("<h2>Beneficios y condiciones</h2>")
    p.append("<p>El paquete de compensacion incluye ademas del salario base una serie de beneficios orientados al bienestar del colaborador y su grupo familiar. Estos beneficios se formalizan por contrato desde el primer dia de labores y se revisan de manera periodica conforme a la politica interna de recursos humanos y a la normativa laboral vigente en el pais de contratacion. La empresa garantiza condiciones de trabajo seguras, equipos apropiados para el desempeno de cada funcion y espacios de descanso adecuados dentro de sus instalaciones.</p>")
    ben = "".join(f"<li>{b}</li>" for b in beneficios)
    p.append(f"<ul>{ben}</ul>")
    p.append("<h2>Proceso de postulacion</h2>")
    p.append(f"<p>{proceso} El proceso completo tiene una duracion estimada de dos a tres semanas desde la publicacion de esta convocatoria hasta la incorporacion del candidato seleccionado. Recomendamos revisar cuidadosamente los requisitos antes de postular y preparar la documentacion solicitada para no generar demoras en las etapas de verificacion de datos, referencias laborales y evaluaciones tecnicas o psicologicas que forman parte del filtro estandar de seleccion.</p>")
    p.append("<h2>Etapas de seleccion</h2>")
    p.append("<p>El proceso se compone de cuatro etapas bien definidas. Primero, la revision curricular realizada por el equipo de reclutamiento, que verifica el ajuste entre el perfil publicado y la experiencia declarada. Segundo, una entrevista telefonica o en linea de aproximadamente veinte minutos para confirmar disponibilidad, expectativas salariales y datos basicos. Tercero, la entrevista presencial o tecnica con el jefe directo del area, que profundiza en conocimientos practicos y casos de trabajo reales. Finalmente, la verificacion de referencias y antecedentes antes de extender la oferta formal de empleo con la fecha de incorporacion acordada.</p>")
    p.append("<h2>Recomendaciones finales</h2>")
    p.append("<p>Antes de enviar tu postulacion revisa tu hoja de vida y adapta la informacion al puesto ofertado, destacando aquellas experiencias que guarden relacion directa con las funciones descritas. Una postulacion bien presentada, sin errores ortograficos y con datos de contacto actualizados, aumenta considerablemente las posibilidades de avanzar a la etapa de entrevista. Te deseamos mucho exito en este nuevo paso de tu carrera profesional y te invitamos a seguir atento a nuestras proximas convocatorias, que publicamos de manera permanente en este portal con oportunidades actualizadas cada semana para distintos sectores, paises y niveles de experiencia laboral.</p>")
    text = "\n".join(p)
    words = len(text.split())
    assert words >= 800, f"muy corto: {words}"
    assert words <= 1100, f"muy largo: {words}"
    return text


def base(i, fecha, titulo, slug, cats, cuerpo_html):
    return f'''\t<item>
\t\t<title>{html.escape(titulo)}</title>
\t\t<link>https://demo.nechugo.news/{slug}/</link>
\t\t<pubDate>{fecha}</pubDate>
\t\t<dc:creator><![CDATA[admin]]></dc:creator>
\t\t<guid isPermaLink="false">https://demo.nechugo.news/?p={1000 + i}</guid>
\t\t<description></description>
\t\t<content:encoded><![CDATA[{cuerpo_html}]]></content:encoded>
\t\t<excerpt:encoded><![CDATA[Convocatoria abierta. Requisitos, funciones, beneficios y como postular. Actualizado este mes.]]></excerpt:encoded>
\t\t<wp:post_id>{1000 + i}</wp:post_id>
\t\t<wp:post_date><![CDATA[{fecha}]]></wp:post_date>
\t\t<wp:post_date_gmt><![CDATA[{fecha}]]></wp:post_date_gmt>
\t\t<wp:comment_status><![CDATA[open]]></wp:comment_status>
\t\t<wp:ping_status><![CDATA[open]]></wp:ping_status>
\t\t<wp:post_name><![CDATA[{slug}]]></wp:post_name>
\t\t<wp:status><![CDATA[publish]]></wp:status>
\t\t<wp:post_parent>0</wp:post_parent>
\t\t<wp:menu_order>0</wp:menu_order>
\t\t<wp:post_type><![CDATA[post]]></wp:post_type>
\t\t<wp:post_password><![CDATA[]]></wp:post_password>
\t\t<wp:is_sticky>0</wp:is_sticky>
\t\t<category domain="category" nicename="empleos"><![CDATA[Empleos]]></category>
\t\t<category domain="post_tag" nicename="demo"><![CDATA[demo]]></category>
\t\t<category domain="category" nicename="{cats[1]}"><![CDATA[{cats[0]}]]></category>
\t</item>
'''


POSTS = [
    (1, "Mon, 06 Oct 2026 08:00:00 +0000", "Grupo Auna busca Ejecutivo(a) Comercial Corporativo", "grupo-auna-ejecutivo-comercial-corporativo", ("Peru", "peru"),
     cuerpo("Ejecutivo Comercial Corporativo", "Grupo Auna", "Peru (Lima)", "indefinido, tiempo completo", "S/ 3,500 mensuales mas comisiones",
        ["Titulo universitario en Administracion, Marketing o carreras afines",
         "Experiencia comprobable de dos anos en ventas B2B o servicios corporativos",
         "Manejo de CRM y paquete Office a nivel intermedio-avanzado",
         "Disponibilidad para visitas comerciales dentro de la ciudad",
         "Licencia de conducir vigente (deseable)"],
        ["Prospectar y captar nuevos clientes corporativos",
         "Elaborar propuestas comerciales y presentaciones de venta",
         "Cumplir metas mensuales y trimestrales asignadas",
         "Dar seguimiento a la cartera de clientes y fidelizar la relacion",
         "Reportar indicadores de gestion al jefe comercial"],
        ["Salario fijo mas comisiones por cumplimiento de metas",
         "Seguro de salud EPS desde el primer dia",
         "Capacitaciones continuas y plan de carrera",
         "Movilidad y viaticos para visitas comerciales",
         "Linea telefonica corporativa"],
        "Los interesados deben enviar su hoja de vida al correo de talento humano indicado en la publicacion original, con el asunto Ejecutivo Comercial Corporativo.")),
    (2, "Fri, 03 Oct 2026 08:00:00 +0000", "Vacantes en Coppel Mexico y como postular", "vacantes-coppel-mexico", ("Mexico", "mexico"),
     cuerpo("Asistente de Tienda y Almacen", "Coppel Mexico", "Mexico (varias ciudades)", "indefinido, tiempo completo", "$ 8,500 MXN mensuales",
        ["Secundaria o preparatoria concluida",
         "Edad entre 18 y 45 anos",
         "Disponibilidad de horario rotativo",
         "Actitud de servicio y trabajo en equipo",
         "Experiencia en retail (deseable, no excluyente)"],
        ["Atencion al cliente en piso de venta",
         "Organizacion y surtido de mercancia en almacen",
         "Inventario y control de existencias",
         "Apoyo en caja en horas pico",
         "Mantenimiento del orden general de la tienda"],
        ["Contratacion directa por la empresa",
         "Prestaciones superiores a la ley",
         "Fondo de ahorro y utilidades",
         "Descuentos en productos de la cadena",
         "Estabilidad y crecimiento interno real"],
        "Las postulaciones se reciben exclusivamente por el portal oficial de empleos de la empresa o directamente en la sucursal mas cercana dejando tu solicitud.")),
    (3, "Wed, 01 Oct 2026 08:00:00 +0000", "Empleos en Coca-Cola FEMSA Mexico: vacantes y requisitos", "empleos-coca-cola-femsa-mexico", ("FABRICA", "fabrica"),
     cuerpo("Operador de Produccion", "Coca-Cola FEMSA", "Mexico (planta industrial)", "indefinido, tiempo completo", "$ 12,000 MXN mensuales",
        ["Preparatoria concluida",
         "Experiencia minima de un ano en plantas de produccion",
         "Conocimiento de normas de seguridad industrial",
         "Disponibilidad para turnos rotativos",
         "Manejo basico de equipos y maquinaria"],
        ["Operar maquinaria de la linea de embotellado",
         "Control de calidad en linea de produccion",
         "Registro de indicadores de produccion",
         "Limpieza y orden del area de trabajo bajo estandares 5S",
         "Apoyo en mantenimiento preventivo basico"],
        ["Salario competitivo con prestaciones de ley ampliadas",
         "Seguro de gastos medicos mayores",
         "Comedor y transporte de personal",
         "Ahorro y fondo de pension",
         "Programa de desarrollo de operadores"],
        "La postulacion se realiza en el portal oficial de la compania adjuntando tu CV; los preseleccionados seran contactados para examen medico y pruebas de aptitud.")),
    (4, "Mon, 29 Sep 2026 08:00:00 +0000", "Oportunidades de empleo en Minera Chinalco", "empleos-minera-chinalco", ("Chile", "chile"),
     cuerpo("Tecnico de Mantenimiento Industrial", "Minera Chinalco", "Chile (region minera)", "contrato por obra o faena", "$ 1,800,000 CLP brutos",
        ["Tecnico profesional en mecanica, electricidad o automatizacion",
         "Experiencia de dos anos en mineria o industria pesada",
         "Licencia interna para trabajo en faenas mineras (deseable)",
         "Conocimiento de planificacion de mantenimiento",
         "Disponibilidad para sistema de turnos 7x7"],
        ["Ejecutar mantenimiento preventivo y correctivo de equipos",
         "Inspeccion de maquinaria pesada y correas transportadoras",
         "Registro de intervenciones en el sistema CMMS",
         "Apoyo en montajes y puestas en marcha",
         "Cumplimiento estricto de estandares de seguridad"],
        ["Renta bruta competitiva del sector minero",
         "Alojamiento y alimentacion en faena",
         "Seguro de salud y mutualidad",
         "Bonos por cumplimiento y antiguedad",
         "Capacitaciones tecnicas certificadas"],
        "Envia tu CV actualizado con certificados a la bolsa de empleo oficial de la faena; el proceso incluye entrevistas tecnicas y revision de antecedentes.")),
    (5, "Thu, 25 Sep 2026 08:00:00 +0000", "Trabajo en Suiza cuidando casas en 2026", "trabajo-suiza-cuidando-casas", ("SUIZA", "suiza"),
     cuerpo("Personal de Cuidado de Residencias (Housekeeping)", "Agencias asociadas en Suiza", "Suiza (cantones de habla alemana)", "contrato temporal con renovacion", "CHF 3,200 mensuales aprox.",
        ["Experiencia en limpieza o cuidado de hogares",
         "Nivel basico de ingles o aleman",
         "Pasaporte vigente y antecedentes limpios",
         "Referencias verificables de empleos anteriores",
         "Disponibilidad para vivir en la propiedad (segun caso)"],
        ["Limpieza y mantenimiento de residencias privadas",
         "Cuidado de plantas y areas exteriores",
         "Control de provisiones y compras basicas",
         "Coordinacion con propietarios y administradores",
         "Reporte de incidencias o danos detectados"],
        ["Salario acorde al mercado suizo",
         "Alojamiento incluido en la mayoria de casos",
         "Seguro de salud obligatorio contratado por el empleador",
         "Descansos semanales pagados",
         "Renovacion de contrato segun desempeno"],
        "Las postulaciones se canalizan a traves de agencias autorizadas; verifica siempre el registro de la agencia antes de pagar cualquier gestion.")),
    (6, "Tue, 23 Sep 2026 08:00:00 +0000", "Oportunidades de empleo para latinos en Canada", "empleos-canada-latinos", ("Canada", "canada"),
     cuerpo("Operario General y Personal de Almacen", "Empresas asociadas en Canada", "Canada (Ontario y Alberta)", "contrato temporal con visa de trabajo", "CAD 18-22 por hora",
        ["Nivel basico o intermedio de ingles",
         "Experiencia en almacenes, agricultura o alimentos (deseable)",
         "Pasaporte vigente con validez minima de dos anos",
         "Disponibilidad para trabajo fisico",
         "Perfil compatible con programas de movilidad temporal"],
        ["Preparacion y empaque de pedidos",
         "Carga y descarga de mercancia",
         "Clasificacion de productos en lineas de produccion",
         "Apoyo en areas agricolas segun temporada",
         "Cumplimiento de normas de higiene y seguridad"],
        ["Pago por hora competitivo del mercado canadiense",
         "Horas extra remuneradas conforme a la ley",
         "Alojamiento asistido durante las primeras semanas",
         "Apoyo con el proceso de permiso de trabajo",
         "Posible via de residencia segun programa"],
        "Revisa los requisitos del programa de movilidad temporal de tu pais y postula con tu CV en ingles; las entrevistas se realizan en linea.")),
    (7, "Fri, 19 Sep 2026 08:00:00 +0000", "Trabajo Disponible en una Fabrica Textil en Espana", "trabajo-fabrica-textil-espana", ("Espana", "espana"),
     cuerpo("Operario/a de Maquina Textil", "Confecciones del Sur SL", "Espana (Valencia)", "contrato temporal con posibilidad de fijo", "1,400 EUR mensuales segun convenio",
        ["No se exige experiencia: formacion inicial remunerada",
         "Carnet de trabajo en Espana o permiso vigente",
         "Disponibilidad para turnos de manana o tarde",
         "Precision manual y buena actitud",
         "Residencia en la zona o disposicion a trasladarse"],
        ["Operar maquinas de confeccion y corte textil",
         "Control de calidad de prendas terminadas",
         "Preparacion de materiales y tejidos",
         "Empaquetado y etiquetado de producto",
         "Orden y limpieza del puesto de trabajo"],
        ["Contrato conforme al convenio textil espanol",
         "Formacion practica remunerada desde el primer dia",
         "Pagas extraordinarias y cotizacion completa",
         "Comedor con precio subvencionado",
         "Posibilidad de contratacion indefinida"],
        "Envia tu candidatura con copia del DNI o NIE a la direccion de contacto publicada; preseleccion telefonica dentro de los cinco dias habiles.")),
    (8, "Wed, 17 Sep 2026 08:00:00 +0000", "Oportunidad de trabajo en fabrica de cajas de carton", "empleo-fabrica-cajas-carton", ("FABRICA", "fabrica"),
     cuerpo("Operario de Maquina Corrugadora", "PackAndina S.A.", "Peru (Lima Norte)", "indefinido, tiempo completo", "S/ 1,800 mas horas extra",
        ["Secundaria completa",
         "Experiencia de seis meses en plantas de empaques (deseable)",
         "Disponibilidad para turnos rotativos",
         "Salud compatible con trabajo fisico",
         "Conocimiento de seguridad industrial basico"],
        ["Operar maquina corrugadora y de corte",
         "Ajuste de formatos segun orden de produccion",
         "Inspeccion visual de calidad de placas",
         "Recuento y apilado de producto terminado",
         "Registro de produccion por turno"],
        ["Planilla completa con todos los beneficios de ley",
         "Horas extra pagadas al 100% adicional",
         "Movilidad y refrigerio en turnos nocturnos",
         "Bono por cumplimiento de metas del area",
         "Oportunidades de ascenso a operario maestro"],
        "Acude con tu CV y DNI a la oficina de personal de la planta o escribe al WhatsApp de reclutamiento publicado; entrevistas presenciales toda la semana.")),
    (9, "Mon, 15 Sep 2026 08:00:00 +0000", "Restaurantes en Estados Unidos contratan personal hispanohablante", "restaurantes-estados-unidos-personal-hispanohablante", ("Estados Unidos", "estados-unidos"),
     cuerpo("Ayudante de Cocina y Mesero(a) Bilingue", "Cadenas de restaurantes asociadas", "Estados Unidos (Texas y Florida)", "tiempo completo, contrato anual", "USD 14-19 por hora mas propinas",
        ["Ingles conversacional basico para comunicarse con el equipo",
         "Experiencia en gastronomia (deseable)",
         "Documentacion legal para trabajar en EE.UU.",
         "Disponibilidad para fines de semana y dias festivos",
         "Presentacion personal e higiene impecables"],
        ["Preparacion de ingredientes y estaciones de cocina",
         "Atencion al cliente en sala y barra",
         "Apoyo en el emplatado bajo supervision del chef",
         "Limpieza y organizacion del area de trabajo",
         "Manejo de pedidos en sistema POS"],
        ["Propinas promedio del sector incluidas en la remuneracion",
         "Comidas de personal durante el turno",
         "Seguro medico despues de los primeros meses",
         "Descuentos en otras sedes de la cadena",
         "Programa interno de ascenso a supervisor de sala"],
        "Aplica directamente en el portal de empleos de la cadena con tu numero de seguro social o permiso de trabajo; entrevistas en persona en cada local.")),
]


def main():
    cats = [("Empleos", "empleos", 10), ("Peru", "peru", 11), ("Mexico", "mexico", 12), ("FABRICA", "fabrica", 13),
            ("Chile", "chile", 14), ("SUIZA", "suiza", 15), ("Canada", "canada", 16), ("Espana", "espana", 17),
            ("Estados Unidos", "estados-unidos", 18)]

    cat_xml = "\n".join(
        f"""\t<wp:category>
\t\t<wp:term_id>{tid}</wp:term_id>
\t\t<wp:category_nicename><![CDATA[{slug}]]></wp:category_nicename>
\t\t<wp:category_parent><![CDATA[]]></wp:category_parent>
\t\t<wp:cat_name><![CDATA[{name}]]></wp:cat_name>
\t</wp:category>
\t<wp:term><wp:term_id>{tid}</wp:term_id><wp:term_taxonomy><![CDATA[category]]></wp:term_taxonomy><wp:term_slug><![CDATA[{slug}]]></wp:term_slug><wp:term_parent><![CDATA[]]></wp:term_parent><wp:term_name><![CDATA[{name}]]></wp:term_name></wp:term>"""
        for name, slug, tid in cats
    )

    items = "\n".join(base(i, f, t, s, c, body) for i, f, t, s, c, body in POSTS)

    wxr = f'''<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
\txmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
\txmlns:content="http://purl.org/rss/1.0/modules/content/"
\txmlns:wfw="http://wellformedweb.org/CommentAPI/"
\txmlns:dc="http://purl.org/dc/elements/1.1/"
\txmlns:wp="http://wordpress.org/export/1.2/">
<channel>
\t<title>nechugo News</title>
\t<link>https://demo.nechugo.news</link>
\t<description>Portal de empleos demo</description>
\t<pubDate>Mon, 06 Oct 2026 08:00:00 +0000</pubDate>
\t<language>es</language>
\t<wp:wxr_version>1.2</wp:wxr_version>
\t<wp:base_site_url>https://demo.nechugo.news</wp:base_site_url>
\t<wp:base_blog_url>https://demo.nechugo.news</wp:base_blog_url>
\t<wp:author><wp:author_id>1</wp:author_id><wp:author_login><![CDATA[admin]]></wp:author_login><wp:author_email><![CDATA[admin@nechugo.news]]></wp:author_email><wp:author_display_name><![CDATA[Redaccion NECHUGO NEWS]]></wp:author_display_name><wp:author_first_name><![CDATA[]]></wp:author_first_name><wp:author_last_name><![CDATA[]]></wp:author_last_name></wp:author>

{cat_xml}

{items}
</channel>
</rss>
'''
    with open("demo-posts.xml", "w") as fh:
        fh.write(wxr)
    print("WXR generado con", len(POSTS), "entradas")


if __name__ == "__main__":
    main()
